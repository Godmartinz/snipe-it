<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AlertMenu;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AlertMenuTest extends TestCase
{
    public function test_the_component_renders(): void
    {
        Livewire::actingAs(User::factory()->superuser()->create())
            ->test(AlertMenu::class)
            ->assertStatus(200);
    }

    public function test_placeholder_reserves_bell_footprint(): void
    {
        $placeholder = (new AlertMenu)->placeholder();

        // Placeholder icon deliberately matches whatever glyph the
        // loaded view (livewire/alert-menu.blade.php) renders via
        // <x-icon type="alerts"/>. IconHelper maps "alerts" to
        // fa-flag, so the placeholder uses fa-flag too — otherwise
        // hydration would visibly swap the icon on first paint.
        $this->assertStringContainsString('dropdown', $placeholder);
        $this->assertStringContainsString('fa-flag', $placeholder);
    }

    public function test_placeholder_reserves_badge_footprint(): void
    {
        $placeholder = (new AlertMenu)->placeholder();

        // The hidden .label reserves badge width so hydration with
        // alerts does not push the surrounding top-nav items sideways.
        $this->assertStringContainsString('label label-danger', $placeholder);
        $this->assertStringContainsString('visibility: hidden', $placeholder);
    }
}

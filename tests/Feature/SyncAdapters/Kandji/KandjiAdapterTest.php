<?php

namespace Tests\Feature\SyncAdapters\Kandji;

use App\Models\Statuslabel;
use App\Models\SyncAdapterConfig;
use App\Models\SyncAdapterInstance;
use App\SyncAdapters\Kandji\KandjiAdapter;
use App\SyncAdapters\SyncAdapter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * End-to-end coverage for the Kandji adapter through SyncAdapter.
 * Mocks Kandji's REST API, asserts assets + asset_external_sources land
 * correctly, and that pagination termination on a short page works.
 */
class KandjiAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Statuslabel::factory()->rtd()->create();
    }

    public function test_pulls_devices_from_kandji_and_creates_assets()
    {
        $adapter = $this->configuredKandjiAdapter();

        Http::fake([
            '*/api/v1/devices*' => Http::sequence()
                ->push([
                    $this->kandjiDevice(id: 'aaa-111', device_name: 'workstation-01', model: 'MacBook Pro'),
                    $this->kandjiDevice(id: 'bbb-222', device_name: 'workstation-02', model: 'MacBook Air'),
                ])
                // Empty second page terminates the client's pagination loop.
                ->push([]),
        ]);

        foreach ($adapter->pull() as $record) {
            SyncAdapter::syncFromRecord($record);
        }

        $this->assertDatabaseCount('asset_external_sources', 2);
        $this->assertDatabaseHas('asset_external_sources', ['source' => 'kandji', 'external_id' => 'aaa-111']);
        $this->assertDatabaseHas('asset_external_sources', ['source' => 'kandji', 'external_id' => 'bbb-222']);
        $this->assertDatabaseHas('assets', ['name' => 'workstation-01']);
    }

    public function test_normalized_record_carries_expected_fields()
    {
        $adapter = $this->configuredKandjiAdapter();

        Http::fake([
            '*/api/v1/devices*' => Http::sequence()
                ->push([
                    $this->kandjiDevice(
                        id: 'ccc-333',
                        device_name: 'design-mbp',
                        model: 'MacBook Pro (16-inch, M3)',
                        serial_number: 'ABC123',
                        platform: 'Mac',
                        os_version: '14.2.1',
                        last_check_in: '2026-01-15T10:00:00.000Z',
                    ),
                ])
                ->push([]),
        ]);

        $records = iterator_to_array($adapter->pull());
        $this->assertCount(1, $records);

        $record = $records[0];
        $this->assertSame('kandji', $record->sourceKey);
        $this->assertSame('ccc-333', $record->sourceId);
        $this->assertSame('design-mbp', $record->hostname);
        $this->assertSame('MacBook Pro (16-inch, M3)', $record->hardwareModel);
        $this->assertSame('ABC123', $record->hardwareSerial);
        $this->assertSame('Apple', $record->manufacturer); // inferred from platform=Mac
        $this->assertSame('Mac', $record->os);
        $this->assertSame('14.2.1', $record->osVersion);
        $this->assertNotNull($record->lastSeen);
    }

    public function test_pull_raises_when_base_url_returns_html_instead_of_json()
    {
        // Regression: when the admin pastes the Iru (Kandji) web
        // console URL as the Base URL instead of the API host, Kandji
        // returns 200 OK with an SPA shell (text/html). ->throw() sees
        // 200 and doesn't fire, ->json() decodes to null, and the
        // sync used to complete silently with "0 hosts synced". The
        // throwIfNotJson macro registered in AppServiceProvider now
        // surfaces this as a real error so the admin gets an
        // actionable flash instead of a zero-hosts warning.
        $adapter = $this->configuredKandjiAdapter();

        Http::fake([
            '*/api/v1/devices*' => Http::response(
                '<!DOCTYPE html><html><body><div id="app"></div></body></html>',
                200,
                ['Content-Type' => 'text/html'],
            ),
        ]);

        $this->expectException(\App\Exceptions\SyncAdapterVendorException::class);
        $this->expectExceptionMessage('Verify the adapter Base URL points at the vendor API');
        iterator_to_array($adapter->pull());
    }

    private function configuredKandjiAdapter(): KandjiAdapter
    {
        $instance = SyncAdapterInstance::where('slug', 'kandji')->firstOrFail();
        SyncAdapterConfig::put($instance->id, 'url', 'https://example.api.kandji.io');
        SyncAdapterConfig::put($instance->id, 'token', Crypt::encrypt('fake-kandji-token'));

        return new KandjiAdapter($instance->fresh());
    }

    /**
     * @return array<string, mixed>
     */
    private function kandjiDevice(
        string $id,
        string $device_name = 'device',
        string $model = 'MacBook',
        ?string $serial_number = null,
        ?string $platform = 'Mac',
        ?string $os_version = null,
        ?string $last_check_in = null,
    ): array {
        return [
            'device_id' => $id,
            'device_name' => $device_name,
            'model' => $model,
            'serial_number' => $serial_number,
            'platform' => $platform,
            'os_version' => $os_version,
            'last_check_in' => $last_check_in,
            'asset_tag' => null,
            'assigned_blueprint_id' => null,
            'mdm_enabled' => true,
        ];
    }
}

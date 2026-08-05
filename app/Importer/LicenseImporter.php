<?php

namespace App\Importer;

use App\Models\Asset;
use App\Models\License;

class LicenseImporter extends ItemImporter
{
    public function __construct($filename)
    {
        parent::__construct($filename);
    }

    protected function handle($row)
    {
        // LicenseImporter deliberately does NOT call parent::handle(). The
        // parent unconditionally assigns $this->item entries for every
        // shared field, which conflates "column absent from CSV" with
        // "column present but empty" and prevents empty CSV cells from
        // clearing existing DB values on update. LicenseImporter builds
        // $this->item exclusively via setItemFromCsvIfPresent so absent
        // columns never enter the update payload (preserving DB values)
        // and present-but-empty columns land as empty strings (clearing
        // DB values). See sanitizeItemForStoring override below for the
        // matching pass-through sanitize.
        $this->item = [];

        // Shared lookup fields. Present-and-empty clears the FK; absent
        // preserves it; present-and-set resolves and stores the id.
        foreach ([
            ['category_id', 'category', fn ($v) => $this->createOrFetchCategory($v)],
            ['company_id', 'company', fn ($v) => $this->createOrFetchCompany($v)],
            ['manufacturer_id', 'manufacturer', fn ($v) => $this->createOrFetchManufacturer($v)],
            ['supplier_id', 'supplier', fn ($v) => $this->createOrFetchSupplier($v)],
        ] as [$itemKey, $csvKey, $resolver]) {
            if ($this->csvRowHas($row, $csvKey)) {
                $value = $this->findCsvMatch($row, $csvKey);
                $this->item[$itemKey] = ($value !== '') ? $resolver($value) : null;
            }
        }

        // Shared straight assignments from ItemImporter's field list that
        // apply to licenses.
        $this->setItemFromCsvIfPresent($row, 'name', 'item_name');
        $this->setItemFromCsvIfPresent($row, 'notes');
        $this->setItemFromCsvIfPresent($row, 'purchase_cost');

        if ($this->csvRowHas($row, 'purchase_date')) {
            $raw = $this->findCsvMatch($row, 'purchase_date');
            if ($raw !== '') {
                $this->item['purchase_date'] = $raw;
                $this->item['purchase_date'] = $this->parseOrNullDate('purchase_date');
            } else {
                $this->item['purchase_date'] = null;
            }
        }

        // Serial and asset_tag both need special handling. serial IS a
        // License field so it goes through the helper. asset_tag is captured
        // separately for the seat-checkout logic in createLicenseIfNotExists.
        $this->setItemFromCsvIfPresent($row, 'serial');

        // checkout_class + checkout_target are internal signals for the
        // seat-checkout logic below; neither is fillable on License, so
        // sanitize's fillable filter drops them before the DB write.
        $this->item['checkout_class'] = $this->findCsvMatch($row, 'checkout_class');
        $this->item['checkout_target'] = $this->determineCheckout($row);
        $this->item['created_by'] = $this->created_by;

        $this->createLicenseIfNotExists($row);
    }

    /**
     * Override the base sanitize to skip the reject-empty pass. LicenseImporter
     * populates $this->item exclusively from CSV columns that were present in
     * the row, so an empty value here is an explicit intent to clear the DB
     * field on update. See handle() above for the matching item-population.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function sanitizeItemForStoring($model, $updating = false)
    {
        return collect($this->item)->only($model->getFillable())->toArray();
    }

    /**
     * Create the license if it does not exist.
     *
     * @author Daniel Melzter
     *
     * @since 4.0
     *
     * @return License|mixed|null
     *                            updated @author Jes Vinsmoke
     *
     * @since 6.1
     */
    public function createLicenseIfNotExists(array $row)
    {
        // serial and name are the identity fields for the create-or-update
        // match. Both are populated in handle() via setItemFromCsvIfPresent,
        // so a CSV without those columns will produce empty keys here.
        // Fallback to empty string so the where clauses do not blow up on
        // undefined indexes; the resulting query will match nothing, and the
        // validation error path takes over on the save attempt.
        $serial = $this->item['serial'] ?? '';
        $name = $this->item['name'] ?? '';

        $editingLicense = false;
        $license = License::where('serial', $serial)->where('name', $name)->first();
        if ($license) {
            if (! $this->updating) {

                if ($serial !== '') {
                    $this->log('A matching License '.$name.' with serial '.$serial.' already exists');
                } else {
                    $this->log('A matching License '.$name.' with no serial number already exists');
                }

                $this->recordSkipped();

                return;
            }

            $this->log('Updating License');
            $editingLicense = true;
        } else {
            $this->log('No Matching License, Creating a new one');
            $license = new License;
        }
        // Asset tag is captured separately for the seat-checkout logic below;
        // it is not itself a License column, so it does not go through the
        // item array (asset_tag isn't in License's fillable).
        $asset_tag = $this->csvRowHas($row, 'asset_tag') ? $this->findCsvMatch($row, 'asset_tag') : '';

        $this->setItemFromCsvIfPresent($row, 'license_email');
        $this->setItemFromCsvIfPresent($row, 'license_name');
        $this->setItemFromCsvIfPresent($row, 'maintained');
        $this->setItemFromCsvIfPresent($row, 'purchase_order');
        $this->setItemFromCsvIfPresent($row, 'order_number');
        $this->setItemFromCsvIfPresent($row, 'reassignable');
        $this->setItemFromCsvIfPresent($row, 'min_amt');
        $this->setItemFromCsvIfPresent($row, 'seats');

        // Dates need parseOrNullDate after the raw value is in $this->item.
        // Empty value stays null (which clears the DB field on update).
        foreach (['expiration_date', 'termination_date'] as $dateField) {
            if ($this->csvRowHas($row, $dateField)) {
                $raw = $this->findCsvMatch($row, $dateField);
                if ($raw !== '') {
                    $this->item[$dateField] = $raw;
                    $this->item[$dateField] = $this->parseOrNullDate($dateField);
                } else {
                    $this->item[$dateField] = null;
                }
            }
        }

        if ($editingLicense) {
            $license->update($this->sanitizeItemForUpdating($license));
        } else {
            $license->fill($this->sanitizeItemForStoring($license));
            $license->created_by = $this->created_by;
        }

        // This sets an attribute on the Loggable trait for the action log
        $license->setImported(true);

        // For new licenses we need to save, for existing ones update() already saved
        $licenseWasSaved = $editingLicense || $license->save();

        if ($licenseWasSaved) {
            $this->log('License '.$name.' with serial number '.$serial.' was created or updated');

            if ($editingLicense) {
                $this->recordUpdated();
            } else {
                $this->recordCreated();
            }

            // Lets try to checkout seats if the fields exist and we have seats.
            if ($license->seats > 0) {
                $checkout_target = $this->item['checkout_target'];
                $asset = Asset::where('asset_tag', $asset_tag)->first();
                $targetLicense = $license->freeSeat();

                if (is_null($targetLicense)) {
                    return;
                }

                if ($checkout_target) {
                    if (! $license->canCheckoutTo($checkout_target)) {
                        $this->log(trans('general.error_checkout_company_mismatch', [
                            'item' => trans('general.license').' "'.$license->name.'"',
                            'item_company' => $license->company?->name ?? trans('general.unassigned'),
                            'target' => ($checkout_target->name ?? $checkout_target->username ?? $checkout_target->id),
                        ]));
                    } else {
                        $targetLicense->assigned_to = $checkout_target->id;
                        $targetLicense->created_by = $this->created_by;
                        if ($asset) {
                            $targetLicense->asset_id = $asset->id;
                        }
                        $targetLicense->save();
                    }
                } elseif ($asset) {
                    if (! $license->canCheckoutTo($asset)) {
                        $this->log(trans('general.error_checkout_company_mismatch', [
                            'item' => trans('general.license').' "'.$license->name.'"',
                            'item_company' => $license->company?->name ?? trans('general.unassigned'),
                            'target' => trans('general.asset').' "'.$asset->display_name.'"',
                        ]));
                    } else {
                        $targetLicense->created_by = $this->created_by;
                        $targetLicense->asset_id = $asset->id;
                        $targetLicense->save();
                    }
                }
            }

            return;
        }
        $this->recordErrored();
        $this->logError($license, 'License "'.$name.'"');
    }
}

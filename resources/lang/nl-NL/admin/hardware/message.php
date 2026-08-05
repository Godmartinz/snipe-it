<?php

return [

    'undeployable' => 'The following assets cannot be deployed and have been removed from checkout: :asset_tags',
    'does_not_exist' => 'Dit asset bestaat niet.',
    'does_not_exist_var' => 'Asset met tag :asset_tag niet gevonden.',
    'no_tag' => 'Geen asset tag opgegeven.',
    'does_not_exist_or_not_requestable' => 'Die asset bestaat niet of is niet aanvraagbaar.',
    'assoc_users' => 'Dit asset is momenteel toegewezen aan een gebruiker en kan niet worden verwijderd. Controleer het asset eerst en probeer het opnieuw. ',
    'warning_audit_date_mismatch' => 'De volgende auditdatum van dit asset (:next_audit_date) ligt vóór de laatste auditdatum (:last_audit_date). Gelieve de volgende auditdatum bij te werken.',
    'labels_generated' => 'Labels were successfully generated.',
    'error_generating_labels' => 'Error while generating labels.',
    'no_assets_selected' => 'No assets selected.',

    'create' => [
        'error' => 'Asset is niet aangemaakt, probeer het opnieuw :(',
        'success' => 'Asset is succesvol aangemaakt. :)',
        'success_linked' => 'Asset met tag :tag is succesvol gemaakt. <strong><a href=":link" style="color: white;">Klik hier om te bekijken</a></strong>.',
        'multi_success_linked' => 'Asset with tag :links was created successfully.|:count assets were created succesfully. :links.',
        'partial_failure' => 'An asset was unable to be created. Reason: :failures|:count assets were unable to be created. Reasons: :failures',
        'target_not_found' => [
            'user' => 'The assigned user could not be found.',
            'asset' => 'The assigned asset could not be found.',
            'location' => 'The assigned location could not be found.',
        ],
    ],

    'update' => [
        'error' => 'Asset is niet gewijzigd, probeer het opnieuw',
        'success' => 'Asset is succesvol bijgewerkt.',
        'encrypted_warning' => 'Asset is succesvol bijgewerkt, maar gecodeerde aangepaste velden hadden geen toegang tot machtigingen',
        'nothing_updated' => 'Geen veld is geselecteerd, er is dus niks gewijzigd.',
        'no_assets_selected' => 'Er zijn geen assets geselecteerd, er is dus niets bijgewerkt.',
        'assets_do_not_exist_or_are_invalid' => 'Geselecteerde assets kunnen niet worden bijgewerkt.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'Asset is niet hersteld, probeer het opnieuw',
        'success' => 'Asset is succesvol hersteld.',
        'bulk_success' => 'Asset is succesvol hersteld.',
        'nothing_updated' => 'Er zijn geen assets geselecteerd, er is dus niets hersteld.',
    ],

    'audit' => [
        'error' => 'Asset audit mislukt: :error ',
        'success' => 'Asset audit succesvol geregistreerd.',
    ],

    'deletefile' => [
        'error' => 'Bestand is niet verwijderd. Probeer het opnieuw.',
        'success' => 'Bestand is met succes verwijderd.',
    ],

    'upload' => [
        'error' => 'Bestand(en) zijn niet geüpload. Probeer het opnieuw.',
        'success' => 'Bestand(en) zijn met succes geüpload.',
        'nofiles' => 'Je hebt geen bestanden geselecteerd om te uploaden, of het bestand wat je probeert te uploaden is te groot',
        'invalidfiles' => 'Een of meer van uw bestanden is te groot of is een bestandstype dat niet is toegestaan. Toegestaande bestandstypen png, gif, jpg, doc, docx, pdf en txt.',
    ],

    'import' => [
        'import_button' => 'Import verwerken',
        'error' => 'Sommige items zijn niet goed geïmporteerd.',
        'errorDetail' => 'De volgende items zijn niet geïmporteerd vanwege fouten.',
        'success' => 'Je bestand is geïmporteerd',
        'file_delete_success' => 'Je bestand is succesvol verwijderd',
        'file_delete_error' => 'Het bestand kon niet worden verwijderd',
        'file_missing' => 'Het geselecteerde bestand ontbreekt',
        'file_already_deleted' => 'Het geselecteerde bestand is al verwijderd',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Een of meer attributen in de kopregel bevatten ongeldige UTF-8-tekens',
        'content_row_has_malformed_characters' => 'Een of meer attributen in de eerste rij inhoud bevat ongeldige UTF-8 tekens',
        'transliterate_failure' => 'Transliteration from :encoding to UTF-8 failed due to invalid characters in input',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => 'Verwijder',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Aangemaakt',
            'updated' => 'Updated',
            'skipped' => 'Skipped as duplicates',
            'errored' => 'Errored',
            'no_changes' => 'The import finished but nothing was created or updated. Every row was skipped, usually because the underlying records already existed. Check the counts below and adjust the CSV or import type if that is not what you expected.',
        ],
        'update_mode_help' => 'When enabled, existing records matched by identity (serial, asset tag, username, etc.) are updated instead of skipped. Any column in your CSV with an empty value will clear the corresponding field on the existing record. Columns you leave out of your CSV entirely are not touched, so existing values are preserved. Required fields (like name and seats on a license) cannot be cleared. Leaving them empty will produce a validation error for that row.',
        'type_required' => 'Please select an import type before continuing.',
        'processing' => 'Processing your import. Please wait until this finishes before closing the page.',
        'backup_running' => 'Running backup before importing. This can take a while on larger files. Please wait.',
        'backup_label' => 'Pre-import backup',
        'backup_complete' => 'Backup complete',
        'import_label' => 'Importeer',
        'required_fields_missing' => 'The following required fields are not mapped: :fields',
        'history' => [
            'missing_asset_tag_identity' => '(missing asset tag)',
            'missing_asset_tag_message' => 'Row skipped: no asset tag provided.',
            'asset_not_found_message' => 'Asset with this tag does not exist. Import assets first, then re-run the history import.',
            'user_not_matched_message' => 'No user matched ":name" - toggle the match-by options in step 1 or create the user first.',
        ],
        'wizard' => [
            'step_type' => 'Choose type',
            'step_map' => 'Map fields',
            'step_preview' => 'Voorbeeld',
            'back' => 'Terug',
            'next' => 'Volgende',
            'preview_button' => 'Voorbeeld',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'Weet je zeker dat je dit asset wilt verwijderen?',
        'error' => 'Er was een probleem tijdens het verwijderen van het asset. Probeer het opnieuw.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag is currently checked out. Check in this device before deletion.|[2,*]Asset Tags: :asset_tag are currently checked out. Check in these devices before deletion.',
        'nothing_updated' => 'Er zijn geen assets geselecteerd, er is dus niets verwijderd.',
        'success' => 'Het asset is succesvol verwijderd.',
    ],

    'checkout' => [
        'error' => 'Asset is niet uitgecheckt, probeer het opnieuw',
        'success' => 'Asset is met succes uitgecheckt.',
        'user_does_not_exist' => 'De gebruiker is ongeldig. Probeer het opnieuw.',
        'not_available' => 'Dat asset is niet beschikbaar voor check-out!',
        'no_assets_selected' => 'U moet minstens één asset selecteren uit de lijst',
    ],

    'multi-checkout' => [
        'error' => 'Asset was not checked out, please try again|Assets were not checked out, please try again',
        'success' => 'Asset checked out successfully.|Assets checked out successfully.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'U moet minstens één asset selecteren uit de lijst',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'U moet minstens één asset selecteren uit de lijst',
    ],

    'checkin' => [
        'error' => 'Asset is niet ingecheckt, probeer het opnieuw',
        'success' => 'Asset is met succes ingecheckt.',
        'user_does_not_exist' => 'De gebruiker is ongeldig. Probeer het opnieuw.',
        'already_checked_in' => 'Dat asset is al ingecheckt.',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'Request was not successful, please try again.',
        'success' => 'Request successfully submitted.',
        'canceled' => 'Request successfully canceled.',
        'cancel' => 'Annuleer deze aanvraag',
    ],

];

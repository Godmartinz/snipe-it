<?php

return [

    'undeployable' => 'The following assets cannot be deployed and have been removed from checkout: :asset_tags',
    'does_not_exist' => 'Основното средство не постои.',
    'does_not_exist_var' => 'Средство со ознака :asset_tag не е пронајдено.',
    'no_tag' => 'Не е обезбедена ознака за средството.',
    'does_not_exist_or_not_requestable' => 'Тоа средство не постои или не е побараливо.',
    'assoc_users' => 'Ова средство е задолжено на корисник и не може да се избрише. Проверете го, а потоа пробајте повторно да го избришете. ',
    'warning_audit_date_mismatch' => 'Следниот датум на ревизија на ова средство (:next_audit_date) е пред последниот датум на ревизија (:last_audit_date). Ажурирајте го следниот датум на ревизија.',
    'labels_generated' => 'Labels were successfully generated.',
    'error_generating_labels' => 'Error while generating labels.',
    'no_assets_selected' => 'No assets selected.',

    'create' => [
        'error' => 'Основното средство не е креирано, обидете се повторно. :(',
        'success' => 'Основното средство е успешно креирано. :)',
        'success_linked' => 'Средство со ознака :tag беше создадено успешно. <strong><a href=":link" style="color: white;">Кликнете овде за да видите</a></strong>.',
        'multi_success_linked' => 'Asset with tag :links was created successfully.|:count assets were created succesfully. :links.',
        'partial_failure' => 'An asset was unable to be created. Reason: :failures|:count assets were unable to be created. Reasons: :failures',
        'target_not_found' => [
            'user' => 'The assigned user could not be found.',
            'asset' => 'The assigned asset could not be found.',
            'location' => 'The assigned location could not be found.',
        ],
    ],

    'update' => [
        'error' => 'Основното средство не е ажурирано, обидете се повторно',
        'success' => 'Основното средство е успешно ажурирано.',
        'encrypted_warning' => 'Средството успешно се ажурираше, но не беа енкиптираните полиња поради овластувањата',
        'nothing_updated' => 'Не беа избрани полиња, затоа ништо не беше ажурирано.',
        'no_assets_selected' => 'Не беа избрани средства, така што ништо не се ажурираше.',
        'assets_do_not_exist_or_are_invalid' => 'Избраните средства не можат да се ажурираат.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'Основното средство не е вратено, обидете се повторно',
        'success' => 'Основното средство е успешно вратено.',
        'bulk_success' => 'Основното средство е успешно вратено.',
        'nothing_updated' => 'Не беа избрани средства, така што ништо не беше обновено.',
    ],

    'audit' => [
        'error' => 'Ревизија на средства неуспешна: :error ',
        'success' => 'Ревизијата на основни средства е логирана.',
    ],

    'deletefile' => [
        'error' => 'Датотеката не се избриша. Обидете се повторно.',
        'success' => 'Датотеката е успешно избришана.',
    ],

    'upload' => [
        'error' => 'Датотеките не се прикачени. Обидете се повторно.',
        'success' => 'Успешно се преземени датотеките.',
        'nofiles' => 'Не одбравте датотеки за прикачување, или датотеката што сакате да ја поставите е премногу голема',
        'invalidfiles' => 'Една или повеќе од вашите датотеки е преголема или е тип на датотека што не е дозволен. Дозволени типови на датотеки се png, gif, jpg, doc, docx, pdf и txt.',
    ],

    'import' => [
        'import_button' => 'Направи увоз',
        'error' => 'Некои ставки не се увезоа правилно.',
        'errorDetail' => 'Следниве елементи не се увезени поради грешки.',
        'success' => 'Вашата датотека е увезена',
        'file_delete_success' => 'Вашата датотека е избришана',
        'file_delete_error' => 'Датотеката не можеше да се избрише',
        'file_missing' => 'Избраната датотека недостасува',
        'file_already_deleted' => 'Избраната датотека е веќе избришана',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Еден или повеќе атрибути во заглавието се содржат неправилни UTF-8 карактери',
        'content_row_has_malformed_characters' => 'Еден или повеќе атрибути во првиот ред на содржина содржат неправилноUTF-8 карактери',
        'transliterate_failure' => 'Transliteration from :encoding to UTF-8 failed due to invalid characters in input',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => 'Избриши',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Креиран',
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
        'import_label' => 'Увоз',
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
            'step_preview' => 'Preview',
            'back' => 'Назад',
            'next' => 'Следно',
            'preview_button' => 'Preview',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'Дали сте сигурни дека сакате да го избришете ова основно средство?',
        'error' => 'Имаше проблем со бришење на основното средство. Обидете се повторно.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag is currently checked out. Check in this device before deletion.|[2,*]Asset Tags: :asset_tag are currently checked out. Check in these devices before deletion.',
        'nothing_updated' => 'Не беа избрани основни средства, затоа ништо не беше избришано.',
        'success' => 'Основното средство беше избришано.',
    ],

    'checkout' => [
        'error' => 'Основното средство не беше задолжено, обидете се повторно',
        'success' => 'Основното средство е задолжено.',
        'user_does_not_exist' => 'Корисникот е неважечки. Обидете се повторно.',
        'not_available' => 'Основното средство не е достапно за задолжување!',
        'no_assets_selected' => 'Мора да одберете најмалку едно основно средство',
    ],

    'multi-checkout' => [
        'error' => 'Asset was not checked out, please try again|Assets were not checked out, please try again',
        'success' => 'Asset checked out successfully.|Assets checked out successfully.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'Мора да одберете најмалку едно основно средство',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Мора да одберете најмалку едно основно средство',
    ],

    'checkin' => [
        'error' => 'Основното средство не беше раздолжено, обидете се повторно',
        'success' => 'Основното средство е раздолжено.',
        'user_does_not_exist' => 'Корисникот е неважечки. Обидете се повторно.',
        'already_checked_in' => 'Основното средство е веќе задолжено.',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'Request was not successful, please try again.',
        'success' => 'Request successfully submitted.',
        'canceled' => 'Request successfully canceled.',
        'cancel' => 'Откажи го ова барање',
    ],

];

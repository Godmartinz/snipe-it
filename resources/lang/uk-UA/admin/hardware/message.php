<?php

return [

    'undeployable' => 'Ці активи неможливо призначити, тому їх було вилучено з процесу видачі: :asset_tags',
    'does_not_exist' => 'Медіафайл не існує.',
    'does_not_exist_var' => 'Активу з тегом :asset_tag не знайдено.',
    'no_tag' => 'Тег Активу не надано.',
    'does_not_exist_or_not_requestable' => 'Цей актив не існує або його не можна запитувати.',
    'assoc_users' => 'Цей актив в даний час відмічений користувачу і не може бути видалений. Спочатку перевірте активи, а потім спробуйте видалити знову. ',
    'warning_audit_date_mismatch' => 'Ця дата наступного аудиту Активів (:next_audit_date) раніша до дати останнього аудиту (:last_audit_date). Будь ласка, оновіть дату наступного контролю.',
    'labels_generated' => 'Мітки були успішно створені.',
    'error_generating_labels' => 'Помилка при формуванні міток.',
    'no_assets_selected' => 'Не вибрано жодного Актива.',

    'create' => [
        'error' => 'Актив не був створений, будь ласка, спробуйте ще раз :(',
        'success' => 'Актив успішно створений. :)',
        'success_linked' => 'Активу з тегом :tag було успішно створено. <strong><a href=":link" style="color: white;">Натисніть тут, щоб переглянути</a></strong>.',
        'multi_success_linked' => 'Активу з тегом :links було успішно створено.|:count активів було успішно створено. :links.',
        'partial_failure' => 'Актив не може бути створений. Причина: :відмова створення |:count активів. Причина: :відмова',
        'target_not_found' => [
            'user' => 'Не вдалося знайти користувача, якому призначено цей актив.',
            'asset' => 'Не вдалося знайти актив, вибраний для призначення.',
            'location' => 'Призначену локацію не вдалося знайти.',
        ],
    ],

    'update' => [
        'error' => 'Актив не був оновлений, будь ласка, спробуйте ще раз',
        'success' => 'Актив успішно оновлено.',
        'encrypted_warning' => 'Актив успішно оновлений, але зашифровані користувальницькі поля не були із-за дозволів',
        'nothing_updated' => 'Не було обрано жодного поля, тому нічого не було оновлено.',
        'no_assets_selected' => 'Не було обрано медіафайли, тому нічого не було змінено.',
        'assets_do_not_exist_or_are_invalid' => 'Вибрані медіафайли не можуть бути оновлені.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'Актив не був відновлений, будь ласка, спробуйте ще раз',
        'success' => 'Актив успішно відновлено.',
        'bulk_success' => 'Актив успішно відновлено.',
        'nothing_updated' => 'Медіафайли не були вибрані, тому нічого не було відновлено.',
    ],

    'audit' => [
        'error' => 'Помилка аудиту активів: :error ',
        'success' => 'Активу успішно зараховано журнал.',
    ],

    'deletefile' => [
        'error' => 'Файл не видалено. Будь ласка, спробуйте ще раз.',
        'success' => 'Файл успішно видалено.',
    ],

    'upload' => [
        'error' => 'Файл(и) не завантажено. Повторіть спробу.',
        'success' => 'Файл(и) успішно завантажено.',
        'nofiles' => 'Ви не обрали жодного файлу для завантаження, або завеликий файл',
        'invalidfiles' => 'Один або кілька ваших файлів завеликий або є файловим типом, який не допускається. Дозволені типи файлів - png, gif, jpg, doc, docx, pdf, і txt.',
    ],

    'import' => [
        'import_button' => 'Імпорт процесу',
        'error' => 'Деякі елементи не імпортовано належним чином.',
        'errorDetail' => 'Наступні елементи не були імпортовані через помилки.',
        'success' => 'Ваш файл імпортовано',
        'file_delete_success' => 'Ваш файл успішно вилучено',
        'file_delete_error' => 'Файл не може бути видалений',
        'file_missing' => 'Відсутній файл',
        'file_already_deleted' => 'Обраний файл вже видалено',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Один або кілька атрибутів у рядку заголовка містять невірні символи UTF-8',
        'content_row_has_malformed_characters' => 'Один або кілька атрибутів у першому рядку вмісту містять неправильні символи UTF-8',
        'transliterate_failure' => 'Перенесення з :encoding в UTF-8 не вдалося через неприпустимі символи в введенні',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => 'Видалити',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Створено',
            'updated' => 'Оновлено',
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
        'import_label' => 'Імпорт',
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
            'step_preview' => 'Попередній перегляд',
            'back' => 'Назад',
            'next' => 'Далі',
            'preview_button' => 'Попередній перегляд',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'Ви впевнені, що хочете видалити цей медіафайл?',
        'error' => 'Виникла проблема при видаленні активу. Будь ласка, спробуйте ще раз.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag в даний час відмічений. Перевірте цей пристрій перед видаленням. [2,*]Теги актива: :asset_tag наразі перевірені. Перевірте ці пристрої перед видаленням.',
        'nothing_updated' => 'Активи не були вибрані, тому нічого не було видалено.',
        'success' => 'Актив успішно видалений.',
    ],

    'checkout' => [
        'error' => 'Актив не був перевірений, будь ласка, спробуйте ще раз',
        'success' => 'Актив успішно перевірено.',
        'user_does_not_exist' => 'Невірний користувач. Спробуйте ще раз.',
        'not_available' => 'Цей актив недоступний для оформлення!',
        'no_assets_selected' => 'Ви повинні вибрати хоча б один медіафайл зі списку',
    ],

    'multi-checkout' => [
        'error' => 'Актив не був перевірений, будь ласка, спробуйте ще раз|Активи не були відмічені, будь ласка, спробуйте ще раз',
        'success' => 'Актив успішно перевірено. | Активи успішно перевірені.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'Ви повинні вибрати хоча б один медіафайл зі списку',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Ви повинні вибрати хоча б один медіафайл зі списку',
    ],

    'checkin' => [
        'error' => 'Актив не був перевірений, будь ласка, спробуйте ще раз',
        'success' => 'Актив успішно перевірено.',
        'user_does_not_exist' => 'Вказаного користувача не існує. Спробуйте ще раз.',
        'already_checked_in' => 'Цей актив вже перевіряється.',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'Актив не був запитаний, будь ласка, спробуйте ще раз.',
        'success' => 'Запит успішно надіслано.',
        'canceled' => 'Запит успішно скасовано.',
        'cancel' => 'Відмінити цей запит на додавання',
    ],

];

<?php

return [

    'undeployable' => 'The following assets cannot be deployed and have been removed from checkout: :asset_tags',
    'does_not_exist' => 'دارایی وجود ندارد.',
    'does_not_exist_var' => 'Asset with tag :asset_tag not found.',
    'no_tag' => 'No asset tag provided.',
    'does_not_exist_or_not_requestable' => 'آن دارایی وجود ندارد یا قابل درخواست نیست.
',
    'assoc_users' => 'این دارایی در حال حاضر به یک کاربر چک کردن و پاک نمی شود. لطفا دارایی در اولین بار چک کنید، و سپس سعی کنید دوباره حذف کنید.',
    'warning_audit_date_mismatch' => 'This asset\'s next audit date (:next_audit_date) is before the last audit date (:last_audit_date). Please update the next audit date.',
    'labels_generated' => 'Labels were successfully generated.',
    'error_generating_labels' => 'Error while generating labels.',
    'no_assets_selected' => 'No assets selected.',

    'create' => [
        'error' => 'دارایی ساخته نشده است، لطفا دوباره تلاش کنید.',
        'success' => 'دارایی موفقیت ایجاد شده است. :)',
        'success_linked' => 'Asset with tag :tag was created successfully. <strong><a href=":link" style="color: white;">Click here to view</a></strong>.',
        'multi_success_linked' => 'Asset with tag :links was created successfully.|:count assets were created succesfully. :links.',
        'partial_failure' => 'An asset was unable to be created. Reason: :failures|:count assets were unable to be created. Reasons: :failures',
        'target_not_found' => [
            'user' => 'The assigned user could not be found.',
            'asset' => 'The assigned asset could not be found.',
            'location' => 'The assigned location could not be found.',
        ],
    ],

    'update' => [
        'error' => 'دارایی به روز نیست، لطفا دوباره امتحان کنید',
        'success' => 'دارایی ها با موفقیت به روز رسانی.',
        'encrypted_warning' => 'Asset updated successfully, but encrypted custom fields were not due to permissions',
        'nothing_updated' => 'هیچ زمینه، انتخاب شدند تا هیچ چیز به روز شد.',
        'no_assets_selected' => 'هیچ دارایی انتخاب نشد، بنابراین چیزی به‌روزرسانی نشد.
',
        'assets_do_not_exist_or_are_invalid' => 'Selected assets cannot be updated.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'دارایی بازیابی نشد، لطفا دوباره تلاش کنید',
        'success' => 'دارایی با موفقیت بازیابی شد.',
        'bulk_success' => 'دارایی با موفقیت بازیابی شد.',
        'nothing_updated' => 'No assets were selected, so nothing was restored.',
    ],

    'audit' => [
        'error' => 'Asset audit unsuccessful: :error ',
        'success' => 'حسابرسی املاک با موفقیت وارد شد',
    ],

    'deletefile' => [
        'error' => 'فایل حذف نمی شود. لطفا دوباره تلاش کنید.',
        'success' => 'فایل با موفقیت حذف شده است.',
    ],

    'upload' => [
        'error' => 'فایل) آپلود نیست. لطفا دوباره تلاش کنید.',
        'success' => 'فایل (موفقیت آپلود شد.',
        'nofiles' => 'شما هر فایل برای آپلود انتخاب کنید، و یا فایل شما در حال تلاش برای آپلود بیش از حد بزرگ است',
        'invalidfiles' => 'یک یا بیشتر از فایل های خود را بیش از حد بزرگ است یا یک نوع فایل است که مجاز است. انواع فایل های مجاز عبارتند از PNG، GIF، JPG، DOC، DOCX، PDF، TXT و.',
    ],

    'import' => [
        'import_button' => 'Process Import',
        'error' => 'بعضی از موارد به درستی وارد نشدند.',
        'errorDetail' => 'موارد زیر به علت خطا وارد نشده است.',
        'success' => 'فایل شما وارد شده است',
        'file_delete_success' => 'فایل شما با موفقیت حذف شده است',
        'file_delete_error' => 'فایل قابل حذف نشد',
        'file_missing' => 'The file selected is missing',
        'file_already_deleted' => 'The file selected was already deleted',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'One or more attributes in the header row contain malformed UTF-8 characters',
        'content_row_has_malformed_characters' => 'One or more attributes in the first row of content contain malformed UTF-8 characters',
        'transliterate_failure' => 'Transliteration from :encoding to UTF-8 failed due to invalid characters in input',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => 'حذف',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'ایجاد شده',
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
        'import_label' => 'واردات',
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
            'step_preview' => 'پیش نمایش',
            'back' => 'بازگشت',
            'next' => 'بعدی',
            'preview_button' => 'پیش نمایش',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'آیا شما مطمئن هستید که می خواهید این تنظیمات دارایی را حذف کنید؟',
        'error' => 'اشکال در حذف دارایی.لطفا دوباره تلاش کنید.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag is currently checked out. Check in this device before deletion.|[2,*]Asset Tags: :asset_tag are currently checked out. Check in these devices before deletion.',
        'nothing_updated' => 'هیچ دارایی انتخاب نشده بود، بنابراین هیچ چیز حذف نشد.',
        'success' => 'دارایی با موفقیت حذف شد.',
    ],

    'checkout' => [
        'error' => 'دارایی در بررسی نیست، لطفا دوباره امتحان کنید',
        'success' => 'دارایی را بررسی کنید موفقیت.',
        'user_does_not_exist' => 'کاربر نامعتبر است لطفا دوباره امتحان کنید.',
        'not_available' => 'این دارایی برای پرداخت در دسترس نیست!',
        'no_assets_selected' => 'شما حداقل باید یک دارایی از لیست انتخاب کنید',
    ],

    'multi-checkout' => [
        'error' => 'Asset was not checked out, please try again|Assets were not checked out, please try again',
        'success' => 'Asset checked out successfully.|Assets checked out successfully.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'شما حداقل باید یک دارایی از لیست انتخاب کنید',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'شما حداقل باید یک دارایی از لیست انتخاب کنید',
    ],

    'checkin' => [
        'error' => 'دارایی در بررسی نیست، لطفا دوباره امتحان کنید',
        'success' => 'دارایی ها با موفقیت در بررسی.',
        'user_does_not_exist' => 'آن کاربر نامعتبر است. لطفا دوباره سعی کنید.',
        'already_checked_in' => 'دارایی ها که در حال حاضر انتخاب شده است.',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'Request was not successful, please try again.',
        'success' => 'Request successfully submitted.',
        'canceled' => 'Request successfully canceled.',
        'cancel' => 'Cancel this item request',
    ],

];

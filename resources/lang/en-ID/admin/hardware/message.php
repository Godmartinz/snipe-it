<?php

return [

    'undeployable' => 'The following assets cannot be deployed and have been removed from checkout: :asset_tags',
    'does_not_exist' => 'Aset tidak ada.',
    'does_not_exist_var' => 'Asset with tag :asset_tag not found.',
    'no_tag' => 'No asset tag provided.',
    'does_not_exist_or_not_requestable' => 'That asset does not exist or is not requestable.',
    'assoc_users' => 'Aset ini saat ini diperiksa ke pengguna dan tidak dapat dihapus. Harap periksa dulu asetnya, lalu coba hapus lagi. ',
    'warning_audit_date_mismatch' => 'This asset\'s next audit date (:next_audit_date) is before the last audit date (:last_audit_date). Please update the next audit date.',
    'labels_generated' => 'Labels were successfully generated.',
    'error_generating_labels' => 'Error while generating labels.',
    'no_assets_selected' => 'No assets selected.',

    'create' => [
        'error' => 'Aset tidak dibuat, coba lagi. :(',
        'success' => 'Aset berhasil dibuat. :)',
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
        'error' => 'Aset tidak diperbarui, coba lagi',
        'success' => 'Aset Berhasil diperbarui.',
        'encrypted_warning' => 'Asset updated successfully, but encrypted custom fields were not due to permissions',
        'nothing_updated' => 'Tidak ada kategori yang dipilih, jadi tidak ada yang diperbarui.',
        'no_assets_selected' => 'No assets were selected, so nothing was updated.',
        'assets_do_not_exist_or_are_invalid' => 'Selected assets cannot be updated.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'Aset tidak dikembalikan, coba lagi',
        'success' => 'Aset Berhasil dikembalikan.',
        'bulk_success' => 'Aset Berhasil dikembalikan.',
        'nothing_updated' => 'No assets were selected, so nothing was restored.',
    ],

    'audit' => [
        'error' => 'Asset audit unsuccessful: :error ',
        'success' => 'Audit aset berhasil dimasuki.',
    ],

    'deletefile' => [
        'error' => 'Berkas tidak terhapus. Silahkan coba lagi.',
        'success' => 'File berhasil dihapus.',
    ],

    'upload' => [
        'error' => 'Berkas(s) tidak diunggah. Silahkan coba lagi.',
        'success' => 'Berkas(s) berhasil diunggah.',
        'nofiles' => 'Anda tidak memilih file untuk diunggah, atau file yang ingin Anda unggah terlalu besar',
        'invalidfiles' => 'Satu atau lebih berkas anda terlalu besar atau jenis berkas tidak dibolehkan. Jenis berkas yang dibolehkan adalah png, gif, jpg, doc, docx, pdf, dan txt.',
    ],

    'import' => [
        'import_button' => 'Process Import',
        'error' => 'Beberapa item tidak diimpor dengan benar.',
        'errorDetail' => 'Item berikut tidak diimpor karena kesalahan.',
        'success' => 'File Anda telah diimpor',
        'file_delete_success' => 'File anda telah berhasil dihapus',
        'file_delete_error' => 'File tidak dapat dihapus',
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
            'confirm_button' => 'Hapus',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Dibuat',
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
        'import_label' => 'Impor',
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
            'back' => 'Kembali',
            'next' => 'Selanjutnya',
            'preview_button' => 'Preview',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'Yakin ingin menghapus aset ini?',
        'error' => 'Terjadi masalah saat menghapus aset. Silahkan coba lagi.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag is currently checked out. Check in this device before deletion.|[2,*]Asset Tags: :asset_tag are currently checked out. Check in these devices before deletion.',
        'nothing_updated' => 'Tidak ada aset yang dipilih, jadi tidak ada yang diperbarui.',
        'success' => 'Aset berhasil dihapus.',
    ],

    'checkout' => [
        'error' => 'Aset tidak dapat diperiksa, silahkan coba lagi',
        'success' => 'Aset berhasil diperiksa.',
        'user_does_not_exist' => 'Pengguna tidak cocok. Silahkan coba lagi.',
        'not_available' => 'Aset itu tersebut tidak tersedia untuk checkout!',
        'no_assets_selected' => 'Anda harus memilih setidaknya satu aset dari daftar',
    ],

    'multi-checkout' => [
        'error' => 'Asset was not checked out, please try again|Assets were not checked out, please try again',
        'success' => 'Asset checked out successfully.|Assets checked out successfully.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'Anda harus memilih setidaknya satu aset dari daftar',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Anda harus memilih setidaknya satu aset dari daftar',
    ],

    'checkin' => [
        'error' => 'Aset tidak dicek, coba lagi',
        'success' => 'Aset berhasil dicek.',
        'user_does_not_exist' => 'Pengguna tidak cocok. Silahkan coba lagi.',
        'already_checked_in' => 'Aset tersebut sudah diperiksa.',
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

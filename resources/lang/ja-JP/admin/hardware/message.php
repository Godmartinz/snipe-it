<?php

return [

    'undeployable' => 'The following assets cannot be deployed and have been removed from checkout: :asset_tags',
    'does_not_exist' => '資産が存在しません。',
    'does_not_exist_var' => 'タグ:asset_tag を持つアセットが見つかりません。',
    'no_tag' => 'アセットタグが提供されていません。',
    'does_not_exist_or_not_requestable' => 'その資産は存在しないか要求可能ではありません。',
    'assoc_users' => 'この資産はユーザーに貸し出されているため削除できません。資産を返却後、もう一度、やり直して下さい。 ',
    'warning_audit_date_mismatch' => 'この資産の次の監査日 (:next_audit_date) は最終監査日 (:last_audit_date) より前です。次の監査日を更新してください。',
    'labels_generated' => 'ラベルの生成に成功しました。',
    'error_generating_labels' => 'ラベルを生成中にエラーが発生しました。',
    'no_assets_selected' => '資産が選択されていません。',

    'create' => [
        'error' => '資産は作成されませんでした。もう一度、やり直して下さい。',
        'success' => '資産は作成されました。',
        'success_linked' => ':tag を持つアセットは正常に作成されました。 <strong><a href=":link" style="color: white;"></a></strong> を表示するにはここをクリックしてください。',
        'multi_success_linked' => 'タグ:links のアセットが正常に作成されました。|:count アセットが正常に作成されました。',
        'partial_failure' => 'An asset was unable to be created. Reason: :failures|:count assets were unable to be created. Reasons: :failures',
        'target_not_found' => [
            'user' => '割り当てられたユーザーが見つかりません。',
            'asset' => '割り当てられた資産が見つかりません。',
            'location' => '割り当てられた場所が見つかりません。',
        ],
    ],

    'update' => [
        'error' => '資産は更新されませんでした。もう一度、やり直して下さい。',
        'success' => '資産は正常に更新されました。',
        'encrypted_warning' => '資産は正常に更新されましたが、権限が原因で暗号化されたカスタム項目がありませんでした',
        'nothing_updated' => 'フィールドが選択されていないため、更新されませんでした。',
        'no_assets_selected' => '資産が選択されていないため、何も更新されませんでした。',
        'assets_do_not_exist_or_are_invalid' => '選択したアセットは更新できません。',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => '資産は復元されませんでした。もう一度、やり直して下さい。',
        'success' => '資産は正常に復元されました。',
        'bulk_success' => '資産は正常に復元されました。',
        'nothing_updated' => '資産が選択されていないため、何も復元されませんでした。',
    ],

    'audit' => [
        'error' => '資産監査に失敗しました: :error ',
        'success' => '資産の監査ログに記録しました。',
    ],

    'deletefile' => [
        'error' => 'ファイルが削除できませんでした。もう一度、やり直して下さい。',
        'success' => 'ファイルは正常に削除されました。',
    ],

    'upload' => [
        'error' => 'ファイルがアップロードできませんでした。もう一度、やり直して下さい。',
        'success' => 'ファイルが正常にアップロードされました。',
        'nofiles' => 'アップロードするファイルが選択されていないか、アップロードしようとしているファイルが大き過ぎます。',
        'invalidfiles' => 'いずれかのファイルが大き過ぎるか、ファイルタイプが許可されていません。許可されているファイルタイプ（png, gif, jpg, doc, docx, pdf, and txt）',
    ],

    'import' => [
        'import_button' => 'Process Import',
        'error' => 'いくつかの項目は正しくインポートされませんでした。',
        'errorDetail' => '以下のアイテムはエラーのためインポートできませんでした',
        'success' => 'ファイルはインポートされました。',
        'file_delete_success' => 'ファイルを削除しました。',
        'file_delete_error' => 'ファイルが削除出来ませんでした。',
        'file_missing' => '選択されたファイルがありません',
        'file_already_deleted' => '選択したファイルは既に削除されています',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'ヘッダー行の1つ以上の属性に不正な形式のUTF-8文字が含まれています',
        'content_row_has_malformed_characters' => 'コンテンツの最初の行の1つまたは複数の属性に不正な形式のUTF-8文字が含まれています',
        'transliterate_failure' => ':encoding から UTF-8 への変換に失敗しました。入力中の無効な文字が原因です。',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => '削除',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => '作成日時',
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
        'import_label' => 'インポート',
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
            'back' => '戻る',
            'next' => '次へ',
            'preview_button' => 'Preview',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'この資産を削除してもよろしいですか？',
        'error' => '資産を削除する際に問題が発生しました。もう一度やり直して下さい。',
        'assigned_to_error' => '{1}アセットタグ: :asset_tag は現在チェックアウトされています。削除する前にこのデバイスを確認してください。 [2,*]アセットタグ: :asset_tag は現在チェックアウトされています。削除する前にこれらのデバイスを確認してください。',
        'nothing_updated' => '資産が選択されていないため、削除されませんでした。',
        'success' => '資産は正常に削除されました。',
    ],

    'checkout' => [
        'error' => '資産はチェックアウトされませんでした。もう一度、やり直して下さい。',
        'success' => '資産は正常にチェックアウトされました。',
        'user_does_not_exist' => 'その利用者は不正です。もう一度、やり直して下さい。',
        'not_available' => 'この資産はチェックアウトできません!',
        'no_assets_selected' => 'リストから少なくとも1つの資産を選択する必要があります',
    ],

    'multi-checkout' => [
        'error' => 'Asset was not checked out, please try again|Assets were not checked out, please try again',
        'success' => 'Asset checked out successfully.|Assets checked out successfully.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'リストから少なくとも1つの資産を選択する必要があります',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'リストから少なくとも1つの資産を選択する必要があります',
    ],

    'checkin' => [
        'error' => '資産はチェックインされませんでした。もう一度、やり直して下さい。',
        'success' => '資産は正常にチェックインされました。',
        'user_does_not_exist' => 'その利用者は不正です。もう一度、やり直して下さい。',
        'already_checked_in' => 'その資産はすでにチェックインしています。',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'リクエストに失敗しました。もう一度やり直してください。',
        'success' => 'リクエストは正常に送信されました。',
        'canceled' => 'リクエストをキャンセルしました。',
        'cancel' => 'このアイテムのリクエストをキャンセル',
    ],

];

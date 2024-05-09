<?php

return [
    'about_assets_title'           => '資産について',
    'about_assets_text'            => '資産はシリアル番号や資産タグで追跡します。資産は特定することが重要な、高価な物であることが多いです。',
    'archived'  				=> 'アーカイブ',
    'asset'  					=> '資産',
    'bulk_checkout'             => '一括チェックアウト',
    'bulk_checkin'              => '資産をチェックイン',
    'checkin'  					=> '資産をチェックイン',
    'checkout'  				=> '資産をチェックアウト',
    'clone'  					=> '資産を複製',
    'deployable'  				=> '配備可能',
    'deleted'  					=> 'この資産は削除されました。',
    'delete_confirm'            => 'この資産を削除してもよろしいですか？',
    'edit'  					=> '資産を編集',
    'model_deleted'  			=> 'この資産モデルは削除されました。資産を復元する前に、モデルを復元する必要があります。',
    'model_invalid'             => 'この資産のモデルは無効です。',
    'model_invalid_fix'         => 'チェックインまたはチェックアウトを試みる前に、資産を編集して修正する必要があります。',
    'requestable'               => '要求可能',
    'requested'				    => '要求済',
    'not_requestable'           => '要求可能ではありません',
    'requestable_status_warning' => '要求可能な状態を変更しない',
    'restore'  					=> '資産を復元',
    'pending'  					=> 'ペンディング',
    'undeployable'  			=> '配備不可',
    'undeployable_tooltip'  	=> 'この資産にはデプロイできないステータスラベルがあります。現時点ではチェックアウトできません。',
    'view'  					=> '資産を表示',
    'csv_error' => 'CSVファイルにエラーがあります:',
    'import_text' => '<p>Upload a CSV that contains asset history. The assets and users MUST already exist in the system, or they will be skipped. Matching assets for history import happens against the asset tag. We will try to find a matching user based on the user\'s name you provide, and the criteria you select below. If you do not select any criteria below, it will simply try to match on the username format you configured in the <code>Admin &gt; General Settings</code>.</p><p>Fields included in the CSV must match the headers: <strong>Asset Tag, Name, Checkout Date, Checkin Date</strong>. Any additional fields will be ignored. </p><p>Checkin Date: blank or future checkin dates will checkout items to associated user.  Excluding the Checkin Date column will create a checkin date with todays date.</p>
    ',
    'csv_import_match_f-l' => '<strong>firstname.lastname</strong> (<code>jane.smith</code>) 形式でユーザーと一致してみてください',
    'csv_import_match_initial_last' => '<strong>最初の姓</strong> (<code>jsmith</code>) フォーマットでユーザーを一致させてみてください',
    'csv_import_match_first' => '<strong>ファーストネーム</strong> (<code>jane</code>) フォーマットでユーザーをマッチさせてみてください',
    'csv_import_match_email' => 'ユーザー名を <strong>メール</strong> で一致させてみてください',
    'csv_import_match_username' => '<strong>ユーザー名</strong> でユーザーを一致させてみてください',
    'error_messages' => 'エラーメッセージ:',
    'success_messages' => '成功メッセージ:',
    'alert_details' => '詳細は以下を確認してください。',
    'custom_export' => 'カスタムエクスポート',
    'mfg_warranty_lookup' => ':manufacturer 保証書の発行状況を検索',
    'user_department' => 'ユーザー部門',
];

<?php

return [

    'undeployable' => 'I seguenti Beni non possono essere consegnati e sono stati rimossi dall\'Assegnazione: :asset_tags',
    'does_not_exist' => 'Questo Asset non esiste.',
    'does_not_exist_var' => 'Bene con tag :asset_tag non trovato.',
    'no_tag' => 'Nessun tag del Bene è stato fornito.',
    'does_not_exist_or_not_requestable' => 'Questo bene non esiste o non è disponibile.',
    'assoc_users' => 'Questo asset è stato assegnato ad un Utente e non può essere cancellato. Per favore Riassegnalo in magazzino,e dopo riprova a cancellarlo.',
    'warning_audit_date_mismatch' => 'La prossima data d\'inventario di questo Bene (:next_audit_date) precede l\'ultima data d\'inventario (:last_audit_date). Si prega di aggiornare la prossima data d\'inventario.',
    'labels_generated' => 'Etichette generate con successo.',
    'error_generating_labels' => 'Errore durante la generazione delle etichette.',
    'no_assets_selected' => 'Nessun Bene selezionato.',

    'create' => [
        'error' => 'L\'asset non è stato creato, riprova per favore. :(',
        'success' => 'L\'asset è stato creato con successo. :)',
        'success_linked' => 'Bene creato con tag :tag . <strong><a href=":link" style="color: white;">Clicca per vedere</a></strong>.',
        'multi_success_linked' => 'Il bene con tag :links è stato creato con successo.|:count beni sono stati creati con successo. :links.',
        'partial_failure' => 'Non è stato possibile creare un bene. Motivo: :failures|Non è stato possibile creare :count beni. Motivi: :failures',
        'target_not_found' => [
            'user' => 'L\'utente assegnato non è stato trovato.',
            'asset' => 'Il Bene assegnato non è stato trovato.',
            'location' => 'La Sede assegnata non è stata trovata.',
        ],
    ],

    'update' => [
        'error' => 'Il bene non è stato aggiornato, si prega di riprovare',
        'success' => 'Bene aggiornato con successo.',
        'encrypted_warning' => 'Asset aggiornato con successo, ma i campi personalizzati crittografati non sono dovuti ai permessi',
        'nothing_updated' => 'Non è stato selezionato nessun campo, nulla è stato aggiornato.',
        'no_assets_selected' => 'Nessun asset è stato selezionato, quindi niente è stato eliminato.',
        'assets_do_not_exist_or_are_invalid' => 'Gli asset selezionati non possono essere aggiornati.',
    ],

    'bulk_update' => [
        'success' => 'Bene aggiornato con successo.|:count Beni aggiornati con successo.',
        'partial' => ':success Bene/i aggiornato con successo, :failed aggiornamenti falliti. Vedi l\'elenco risultati per dettagli maggiori.',
        'error' => 'Nessun Bene aggiornato. Vedi l\'elenco dei risultati per maggiori dettagli.',
    ],

    'restore' => [
        'error' => 'Il bene non è stato ripristinato, riprova',
        'success' => 'Bene ripristinato con successo.',
        'bulk_success' => 'Bene ripristinato con successo.',
        'nothing_updated' => 'Nessun bene selezionato, non è stato ripristinato nulla.',
    ],

    'audit' => [
        'error' => 'Inventario del Bene non riuscito: :error ',
        'success' => 'L\'audit di risorse si è registrato con successo.',
    ],

    'deletefile' => [
        'error' => 'File non cancellato. Riprova.',
        'success' => 'File cancellato con successo.',
    ],

    'upload' => [
        'error' => 'File non caricato/i. Riprova.',
        'success' => 'File caricato/i con successo.',
        'nofiles' => 'Non hai selezionato nessun file per il caricamento, oppure il file selezionato è troppo grande',
        'invalidfiles' => 'Uno o più file è troppo grande o è un tipo di file non consentito. Tipi di file ammessi sono png, gif, jpg, doc, docx, pdf, txt.',
    ],

    'import' => [
        'import_button' => 'Importa Processo',
        'error' => 'Alcuni elementi non sono stati importati correttamente.',
        'errorDetail' => 'Gli articoli seguenti non sono stati importati correttamente a causa di errori.',
        'success' => 'Il file è stato importato con successo',
        'file_delete_success' => 'Il file è stato cancellato con successo',
        'file_delete_error' => 'Impossibile eliminare il file',
        'file_missing' => 'File selezionato mancante',
        'file_already_deleted' => 'Il file selezionato è già stato eliminato',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Uno o più attributi nella riga d\'intestazione contengono caratteri UTF-8 malformati',
        'content_row_has_malformed_characters' => 'Uno o più attributi nella prima riga del contenuto contengono caratteri UTF-8 malformati',
        'transliterate_failure' => 'Traslitterazione da :encoding a UTF-8 non riuscita a causa di caratteri non validi nell\'input',
        'bulk_delete' => [
            'button' => 'Elimina Selezionati (:count)',
            'confirm_title' => 'Eliminare i file di import selezionati?',
            'confirm_body' => 'Stai per eliminare permanentemente :count file d\'importazione. Una volta eseguita, questa operazione non può essere annullata.',
            'confirm_button' => 'Cancella',
            'success' => 'File di importazione eliminato con successo.|:count file di importazione eliminati con successo.',
            'skipped' => ':count file saltati perché non hai i privilegi necessari per eliminarli.',
            'select_all' => 'Seleziona tutti i file su questa pagina',
            'select_row' => 'Seleziona :file per eliminazione di massa',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Creato',
            'updated' => 'Aggiornato',
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
        'import_label' => 'Importa',
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
            'step_preview' => 'Anteprima',
            'back' => 'Indietro',
            'next' => 'Successivo',
            'preview_button' => 'Anteprima',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => 'Sei sicuro di voler eliminare questo bene?',
        'error' => 'C\'è stato un problema durante la cancellazione del bene. Riprova per favore.',
        'assigned_to_error' => '{1}Il tag: :asset_tag è assegnato. Effettua il check in del dispositivo prima di cancellarlo.|[2,*] :asset_tag sono assegnati. Effettua il check-in dei dispositivi prima di cancellarli.',
        'nothing_updated' => 'Nessun patrimonio è stato selezionato, quindi niente è stato eliminato.',
        'success' => 'Il bene è stato eliminato con successo.',
    ],

    'checkout' => [
        'error' => 'Il bene non è stato assegnato, per favore riprova',
        'success' => 'Il bene è stato assegnato con successo.',
        'user_does_not_exist' => 'Questo utente non è valido. Riprova.',
        'not_available' => 'Questo Bene non è disponibile per l\'assegnazione!',
        'no_assets_selected' => 'Devi selezionare almeno un Bene dall\'elenco',
    ],

    'multi-checkout' => [
        'error' => 'L\'assegnazione non è andata a buon fine, riprova|Le assegnazioni non sono andate a buon fine, riprova',
        'success' => 'Bene assegnato correttamente.|Beni assegnati correttamente.',
    ],

    'multi-checkin' => [
        'error' => 'Il Bene non è stato restituito, riprova|I Beni non sono stati restituiti, riprova',
        'success' => 'Bene restituito correttamente.|Beni restituiti correttamente.',
        'no_assets_selected' => 'Devi selezionare almeno un Bene dall\'elenco',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Devi selezionare almeno un Bene dall\'elenco',
    ],

    'checkin' => [
        'error' => 'Il Bene non è stato restituito, riprova',
        'success' => 'Bene restituito con successo.',
        'user_does_not_exist' => 'Questo utente non è valido. Riprova.',
        'already_checked_in' => 'Il prodotto è già rientrato.',
        'force_checkin_orphaned_success' => 'Assegnazione non valida annullata con successo.',
        'force_checkin_not_orphaned' => 'L\'articolo non è in uno stato di assegnazione non valido.',
        'force_checkin_error' => 'Impossibile cancellare l\'assegnazione non valida.',

    ],

    'requests' => [
        'error' => 'Richiesta non riuscita, riprova.',
        'success' => 'Richiesta inviata con successo.',
        'canceled' => 'Richiesta annullata con successo.',
        'cancel' => 'Annulla questa richiesta',
    ],

];

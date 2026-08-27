<?php

return [

    'undeployable' => 'Šis turtas negali būti išduotas, todėl buvo pašalintas iš išduodamų sąrašo: :asset_tags',
    'does_not_exist' => 'Tokio turto nėra.',
    'does_not_exist_var' => 'Turtas su numeriu :asset_tag nerastas.',
    'no_tag' => 'Nenurodytas inventorinis numeris.',
    'does_not_exist_or_not_requestable' => 'Tokio turto nėra arba jo negalima užsakyti.',
    'assoc_users' => 'Šis turtas šiuo metu yra išduotas naudotojui ir negali būti panaikintas. Pirmiausia paimkite turtą ir tuomet vėl bandykite jį panaikinti. ',
    'warning_audit_date_mismatch' => 'Šio turto kito audito data (:next_audit_date) yra ankstesnė už paskutinio audito datą (:last_audit_date). Atnaujinkite kito audito datą.',
    'labels_generated' => 'Etiketės sėkmingai sugeneruotos.',
    'error_generating_labels' => 'Generuojant etiketes įvyko klaida.',
    'no_assets_selected' => 'Nepasirinktas joks turtas.',

    'create' => [
        'error' => 'Turto sukurti nepavyko, bandykite dar kartą.',
        'success' => 'Turtas sėkmingai sukurtas. :)',
        'success_linked' => 'Turtas su žyma :tag sėkmingai sukurtas. <strong><a href=":link" style="color: white;">Spustelėkite čia, kad peržiūrėtumėte</a></strong>.',
        'multi_success_linked' => 'Turtas su inventoriniu numeriu :links sėkmingai sukurtas.|Turto vienetai (:count) sėkmingai sukurti. :links.',
        'partial_failure' => 'Nepavyko sukurti turto. Priežastis: :failures|Turto vienetų (:count) nepavyko sukurti. Priežastys: :failures',
        'target_not_found' => [
            'user' => 'Priskirto naudotojo rasti nepavyko.',
            'asset' => 'Priskirto turto rasti nepavyko.',
            'location' => 'Priskirtos vietos rasti nepavyko.',
        ],
    ],

    'update' => [
        'error' => 'Turto atnaujinti nepavyko, bandykite dar kartą',
        'success' => 'Turtas sėkmingai atnaujintas.',
        'encrypted_warning' => 'Turtas buvo sėkmingai atnaujintas, tačiau dėl nepakankamų teisių, užšifruoti pasirinktiniai laukai nebuvo atnaujinti',
        'nothing_updated' => 'Nebuvo pasirinktas nei vienas laukas, todėl niekas nebuvo atnaujinta.',
        'no_assets_selected' => 'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo atnaujinta.',
        'assets_do_not_exist_or_are_invalid' => 'Pasirinktas turtas negali būti atnaujintas.',
    ],

    'bulk_update' => [
        'success' => 'Turtas sėkmingai atnaujintas.|Turto vienetai (:count) sėkmingai atnaujinti.',
        'partial' => ':success turtas sėkmingai atnaujintas, :failed nepavyko. Daugiau informacijos rasite rezultatų masyve.',
        'error' => 'Nebuvo atnaujintas joks turtas. Išsamesnės informacijos ieškokite rezultatų masyve.',
    ],

    'restore' => [
        'error' => 'Turto atkurti nepavyko, bandykite dar kartą',
        'success' => 'Turtas sėkmingai atkurtas.',
        'bulk_success' => 'Turtas sėkmingai atkurtas.',
        'nothing_updated' => 'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo atkurta.',
    ],

    'audit' => [
        'error' => 'Turto auditas nesėkmingas: :error ',
        'success' => 'Turto auditas sėkmingai užregistruotas.',
    ],

    'deletefile' => [
        'error' => 'Failas neištrintas. Bandykite dar kartą.',
        'success' => 'Failas sėkmingai ištrintas.',
    ],

    'upload' => [
        'error' => 'Failo (-ų) įkelti nepavyko. Bandykite dar kartą.',
        'success' => 'Failas(-ai) sėkmingai įkelti.',
        'nofiles' => 'Nepasirinkote jokio failo įkėlimui arba failas, kurį bandote įkelti, yra per didelis',
        'invalidfiles' => 'Vienas ar keli failai yra per dideli arba neleistinas šis failų formatas. Leidžiami failų tipai yra: png, gif, jpg, doc, docx, pdf ir txt.',
    ],

    'import' => [
        'import_button' => 'Vykdyti importavimą',
        'error' => 'Kai kurie elementai nebuvo tinkamai importuoti.',
        'errorDetail' => 'Šie elementai nebuvo importuoti dėl klaidų.',
        'success' => 'Jūsų failas buvo importuotas',
        'file_delete_success' => 'Jūsų failas buvo sėkmingai ištrintas',
        'file_delete_error' => 'Šio failo ištrinti nepavyko',
        'file_missing' => 'Pažymėtas failas nerastas',
        'file_already_deleted' => 'Pasirinktas failas jau buvo panaikintas',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Vienas ar keli antraštinės eilutės atributai turi netinkamai suformuotų UTF-8 simbolių',
        'content_row_has_malformed_characters' => 'Vienas ar keli pirmosios eilutės atributai turi netinkamai suformuotų UTF-8 simbolių',
        'transliterate_failure' => 'Transliteracija iš :encoding į UTF-8 nepavyko dėl netinkamų įvesties simbolių',
        'bulk_delete' => [
            'button' => 'Ištrinti pasirinktus (:count)',
            'confirm_title' => 'Ištrinti pasirinktus importavimo failus?',
            'confirm_body' => 'Jūs ketinate visam laikui ištrinti :count importo failą (-us). Šio veiksmo negalima atšaukti.',
            'confirm_button' => 'Panaikinti',
            'success' => 'Importavimo failas sėkmingai ištrintas.|Importavimo failai (:count) sėkmingai ištrinti.',
            'skipped' => 'Failai (:count) buvo praleisti, nes neturite teisės jų ištrinti.',
            'select_all' => 'Pažymėti visus šiame puslapyje esančius failus',
            'select_row' => 'Pasirinkite :file masiniam trynimui',
        ],
        'row_count' => '{0} Šiame faile nėra duomenų eilučių|{1} importuotinų duomenų eilučių skaičius (:count)|[2,*] importuotinų duomenų eilučių skaičius (:count)',
        'summary' => [
            'created' => 'Sukurta',
            'updated' => 'Atnaujinta',
            'skipped' => 'Skipped as duplicates',
            'errored' => 'Errored',
            'no_changes' => 'The import finished but nothing was created or updated. Every row was skipped, usually because the underlying records already existed. Check the counts below and adjust the CSV or import type if that is not what you expected.',
        ],
        'update_mode_help' => 'When enabled, existing records matched by identity (serial, asset tag, username, etc.) are updated instead of skipped. Any column in your CSV with an empty value will clear the corresponding field on the existing record. Columns you leave out of your CSV entirely are not touched, so existing values are preserved. Required fields (like name and seats on a license) cannot be cleared. Leaving them empty will produce a validation error for that row.',
        'type_required' => 'Prieš tęsdami pasirinkite importavimo tipą.',
        'processing' => 'Jūsų importavimas apdorojamas. Sulaukite kol jis pasibaigs, prieš uždarydami puslapį.',
        'backup_running' => 'Running backup before importing. This can take a while on larger files. Please wait.',
        'backup_label' => 'Atsarginė kopija prieš importavimą',
        'backup_complete' => 'Atsarginės kopijos kūrimas baigtas',
        'import_label' => 'Importavimas',
        'required_fields_missing' => 'Šie privalomi laukai nėra susieti: :fields',
        'history' => [
            'missing_asset_tag_identity' => '(trūksta inventorinio numerio)',
            'missing_asset_tag_message' => 'Eilutė praleista: nenurodytas inventorinis numeris.',
            'asset_not_found_message' => 'Turto su tokiu inventoriniu numeriu nėra. Pirmiausia importuokite turtą, tada dar kartą paleiskite istorijos importavimą.',
            'user_not_matched_message' => 'Nerastas joks naudotojas, atitinkantis „:name“. Pakeiskite atitikimo parinktis 1‑ame žingsnyje, arba pirmiausia sukurkite naudotoją.',
        ],
        'wizard' => [
            'step_type' => 'Pasirinkite tipą',
            'step_map' => 'Susiekite laukus',
            'step_preview' => 'Peržiūra',
            'back' => 'Grįžti',
            'next' => 'Sekantis',
            'preview_button' => 'Peržiūra',
            'process' => 'Vykdyti importavimą',
            'preview_intro' => 'Atvaizduojamos pirmos eilutės (:count) pritaikius jūsų susiejimą. Jei prieš importavimą reikia pakoreguoti susietus atributus, naudokite mygtuką „Atgal“.',
        ],
    ],

    'delete' => [
        'confirm' => 'Ar tikrai norite panaikinti šį turtą?',
        'error' => 'Bandant panaikinti turtą įvyko klaida. Bandykite dar kartą.',
        'assigned_to_error' => '{1}Inventorinis numeris: :asset_tag šiuo metu yra išduotas. Paimkite šį įrenginį prieš panaikindami.|[2,*]Inventoriniai numeriai: :asset_tag šiuo metu yra išduoti. Paimkite šiuos įrenginius prieš panaikindami.',
        'nothing_updated' => 'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo panaikinta.',
        'success' => 'Turtas sėkmingai panaikintas.',
    ],

    'checkout' => [
        'error' => 'Turtas nebuvo išduotas, bandykite dar kartą',
        'success' => 'Turtas sėkmingai išduotas.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'not_available' => 'Šis turtas negali būti išduodamas!',
        'no_assets_selected' => 'Turite pasirinkti bent vieną turto vienetą iš sąrašo',
    ],

    'multi-checkout' => [
        'error' => 'Turtas nebuvo išduotas, bandykite dar kartą|Turtas nebuvo išduotas, bandykite dar kartą',
        'success' => 'Turtas sėkmingai išduotas.|Turtas sėkmingai išduotas.',
    ],

    'multi-checkin' => [
        'error' => 'Turtas nebuvo paimtas, bandykite dar kartą|Turtas nebuvo paimtas, bandykite dar kartą',
        'success' => 'Turtas sėkmingai paimtas.|Turtas sėkmingai paimtas.',
        'no_assets_selected' => 'Turite pasirinkti bent vieną turto vienetą iš sąrašo',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Turite pasirinkti bent vieną turto vienetą iš sąrašo',
    ],

    'checkin' => [
        'error' => 'Turtas nebuvo paimtas, bandykite dar kartą',
        'success' => 'Turtas sėkmingai paimtas.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'already_checked_in' => 'Šis turtas jau yra paimtas.',
        'force_checkin_orphaned_success' => 'Neteisingas priskyrimas sėkmingai ištrintas.',
        'force_checkin_not_orphaned' => 'Daiktas nėra netinkamo priskyrimo būsenoje.',
        'force_checkin_error' => 'Nepavyko ištrinti neteisingo priskyrimo.',

    ],

    'requests' => [
        'error' => 'Prašymas buvo nesėkmingas, bandykite dar kartą.',
        'success' => 'Prašymas sėkmingai pateiktas.',
        'canceled' => 'Prašymas sėkmingai atšauktas.',
        'cancel' => 'Atšaukti šio daikto užklausą',
    ],

];

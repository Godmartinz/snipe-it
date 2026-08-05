<?php

return [

    'undeployable' => 'Los siguientes activos no se pueden desplegar y se han eliminado del checkout: :asset_tags',
    'does_not_exist' => 'El activo no existe.',
    'does_not_exist_var' => 'Activo con placa :asset_tag no encontrado.',
    'no_tag' => 'No se ha proporcionado ninguna placa de activo.',
    'does_not_exist_or_not_requestable' => 'Ese activo no existe o no puede ser solicitado.',
    'assoc_users' => 'Actualmente este activo está asignado a un usuario y no puede ser eliminado. Por favor, primero ingrese el activo y vuelva a intentarlo. ',
    'warning_audit_date_mismatch' => 'La próxima fecha de auditoría de este activo (:next_audit_date) es anterior a la última fecha de auditoría (:last_audit_date). Por favor, actualice la próxima fecha de auditoría.',
    'labels_generated' => 'Las etiquetas fueron generadas exitosamente.',
    'error_generating_labels' => 'Error en la generación de etiquetas.',
    'no_assets_selected' => 'No se han seleccionado activos.',

    'create' => [
        'error' => 'El activo no fue creado, por favor, inténtelo de nuevo. :(',
        'success' => 'Activo creado con éxito. :)',
        'success_linked' => 'Activo con placa :tag creado con éxito. <strong><a href=":link" style="color: white;">Haga clic aquí para ver</a></strong>.',
        'multi_success_linked' => 'Activo con etiqueta :links fue creado exitosamente.|:count activos fueron creados correctamente. :links.',
        'partial_failure' => 'No se ha podido crear un activo: Motivo: :failures|No se pudieron crear :count activos. Motivos: :failures',
        'target_not_found' => [
            'user' => 'El usuario asignado no pudo ser encontrado.',
            'asset' => 'No se ha encontrado el recurso asignado.',
            'location' => 'No se pudo encontrar la ubicación asignada.',
        ],
    ],

    'update' => [
        'error' => 'El activo no pudo ser actualizado, por favor inténtelo de nuevo',
        'success' => 'Equipo actualizado correctamente.',
        'encrypted_warning' => 'El activo se actualizó correctamente, pero los campos personalizados cifrados no lo hicieron debido a los permisos',
        'nothing_updated' => 'Ningún campo fue seleccionado, por lo que no se actualizó nada.',
        'no_assets_selected' => 'Ningún activo fue seleccionado, por lo que no se actualizó nada.',
        'assets_do_not_exist_or_are_invalid' => 'Los activos seleccionados no se pueden actualizar.',
    ],

    'bulk_update' => [
        'success' => 'Asset updated successfully.|:count assets were updated successfully.',
        'partial' => ':success asset(s) updated successfully, :failed failed. See the results array for details.',
        'error' => 'No assets were updated. See the results array for details.',
    ],

    'restore' => [
        'error' => 'El activo no fue restaurado, por favor inténtelo nuevamente',
        'success' => 'Activo restaurado exitosamente.',
        'bulk_success' => 'Activo restaurado exitosamente.',
        'nothing_updated' => 'No se seleccionaron activos, por lo que no se restauró nada.',
    ],

    'audit' => [
        'error' => 'Auditoría de activos fallida: :error ',
        'success' => 'Auditoría de activos registrada correctamente.',
    ],

    'deletefile' => [
        'error' => 'Archivo no eliminado. Por favor inténtelo nuevamente.',
        'success' => 'Archivo eliminado correctamente.',
    ],

    'upload' => [
        'error' => 'Archivo(s) no cargado(s). Por favor, inténtelo nuevamente.',
        'success' => 'Archivo(s) cargado(s) exitosamente.',
        'nofiles' => 'No seleccionó ningún archivo para ser cargado, o el archivo que está tratando de cargar es demasiado grande',
        'invalidfiles' => 'Uno o más de sus archivos son demasiado grandes o son de un tipo de archivo que no está permitido. Los tipos de archivo permitidos son png, gif, jpg, doc, docx, pdf y txt.',
    ],

    'import' => [
        'import_button' => 'Importar',
        'error' => 'Algunos de los elementos no se importaron correctamente.',
        'errorDetail' => 'Lo siguientes elementos no se importaron debido a errores.',
        'success' => 'Su archivo ha sido importado',
        'file_delete_success' => 'Su archivo se ha eliminado correctamente',
        'file_delete_error' => 'El archivo no se pudo eliminar',
        'file_missing' => 'Falta el archivo seleccionado',
        'file_already_deleted' => 'El archivo seleccionado ya fue eliminado',
        'file_missing_on_disk' => 'The file for this import is no longer on disk. It may have been deleted outside of Snipe-IT. Delete this entry and re-upload the file to try again.',
        'file_empty' => 'This file has no data rows. Nothing can be imported from it.',
        'header_row_missing' => 'This file does not have a recognized header row. Delete this entry and re-upload the file to try again.',
        'header_row_has_malformed_characters' => 'Uno o más atributos en la fila del encabezado contienen caracteres UTF-8 mal formados',
        'content_row_has_malformed_characters' => 'Uno o más atributos en la primera fila contienen caracteres UTF-8 mal formados',
        'transliterate_failure' => 'La transliteración de :encoding a UTF-8 falló debido a caracteres no válidos en la entrada',
        'bulk_delete' => [
            'button' => 'Delete Selected (:count)',
            'confirm_title' => 'Delete selected import files?',
            'confirm_body' => 'You are about to permanently delete :count import file(s). This cannot be undone.',
            'confirm_button' => 'Borrar',
            'success' => 'Import file deleted successfully.|:count import files were deleted successfully.',
            'skipped' => ':count file(s) were skipped because you do not have permission to delete them.',
            'select_all' => 'Select all files on this page',
            'select_row' => 'Select :file for bulk delete',
        ],
        'row_count' => '{0} No data rows in this file|{1} :count data row to import|[2,*] :count data rows to import',
        'summary' => [
            'created' => 'Creado',
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
        'import_label' => 'Importar',
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
            'back' => 'Atrás',
            'next' => 'Siguiente',
            'preview_button' => 'Preview',
            'process' => 'Process import',
            'preview_intro' => 'Previewing the first :count row(s) after applying your mapping. Use the Back button if you need to edit the mapped attributes before importing.',
        ],
    ],

    'delete' => [
        'confirm' => '¿Está seguro de que desea eliminar este activo?',
        'error' => 'Hubo un problema al eliminar el activo. Por favor, inténtelo de nuevo.',
        'assigned_to_error' => '{1}Asset Tag: :asset_tag is currently checked out. Check in this device before deletion.|[2,*]Asset Tags: :asset_tag are currently checked out. Check in these devices before deletion.',
        'nothing_updated' => 'Ningún activo se seleccionó, así que nada fue borrado.',
        'success' => 'El activo se ha eliminado correctamente.',
    ],

    'checkout' => [
        'error' => 'El activo no fue asignado, por favor inténtelo de nuevo',
        'success' => 'Equipo asignado correctamente.',
        'user_does_not_exist' => 'Este usuario no es correcto. Por favor, inténtelo de nuevo.',
        'not_available' => '¡Ese equipo no está disponible para ser asignado!',
        'no_assets_selected' => 'Debe seleccionar al menos un activo de la lista',
    ],

    'multi-checkout' => [
        'error' => 'El activo no fue asignado, por favor, intente nuevamente|Los activos no fueron asignados, por favor, intente nuevamente',
        'success' => 'El activo fue asignado correctamente|Los activos fueron asignados correctamente.',
    ],

    'multi-checkin' => [
        'error' => 'Asset was not checked in, please try again|Assets were not checked in, please try again',
        'success' => 'Asset checked in successfully.|Assets checked in successfully.',
        'no_assets_selected' => 'Debe seleccionar al menos un activo de la lista',
    ],

    'multi-audit' => [
        'success' => ':count asset audited successfully.|:count assets audited successfully.',
        'partial_error' => ':success asset audited, :failed failed. Check the errors below and try again.|:success assets audited, :failed failed. Check the errors below and try again.',
        'no_assets_selected' => 'Debe seleccionar al menos un activo de la lista',
    ],

    'checkin' => [
        'error' => 'El activo no se pudo ingresar, por favor inténtelo de nuevo',
        'success' => 'El activo fue ingresado exitosamente.',
        'user_does_not_exist' => 'Este usuario no es correcto. Por favor, inténtelo de nuevo.',
        'already_checked_in' => 'El equipo ya ha sido recibido.',
        'force_checkin_orphaned_success' => 'Invalid assignment cleared successfully.',
        'force_checkin_not_orphaned' => 'Item is not in an invalid assignment state.',
        'force_checkin_error' => 'Could not clear invalid assignment.',

    ],

    'requests' => [
        'error' => 'La solicitud no se realizó correctamente, por favor inténtelo de nuevo.',
        'success' => 'Solicitud enviada con éxito.',
        'canceled' => 'Petición cancelada con éxito.',
        'cancel' => 'Cancelar solicitud para este elemento',
    ],

];

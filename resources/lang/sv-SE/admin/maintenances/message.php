<?php

return [
    'not_found' => 'Tillgångsunderhållet du letade efter kunde inte hittas!',
    'delete' => [
        'confirm' => 'År du säker på att du vill radera tillgångsunderhållet?',
        'error' => 'Ett fel uppstod vid radering av tillgångsunderhåll. Vänligen försök igen.',
        'success' => 'Tillgångsunderhåll raderat.',
    ],
    'create' => [
        'error' => 'Tillgångsunderhållet kunde inte skapas. Vänligen försök igen.',
        'success' => 'Tillgångsunderhåll skapat.',
    ],
    'edit' => [
        'error' => 'Tillgångsunderhållet kunde inte redigeras. Vänligen försök igen.',
        'success' => 'Tillgångsunderhåll redigerat.',
    ],
    'asset_maintenance_incomplete' => 'Inte färdigställt ännu',
    'warranty' => 'Garanti',
    'not_warranty' => 'Ej garanti',
    'complete' => [
        'confirm' => 'Are you sure you want to mark this maintenance as complete? This cannot be undone.',
        'success' => 'Maintenance marked as complete.',
        'error' => 'There was an issue marking this maintenance as complete. Please try again.',
    ],
    'bulk_delete' => 'No maintenance records were deleted (:skipped skipped).|Deleted :count maintenance record. (:skipped skipped)|Deleted :count maintenance records. (:skipped skipped)',
    'bulk_complete' => 'No maintenance records were marked complete (:skipped skipped or already complete).|Marked :count maintenance record complete. (:skipped skipped or already complete)|Marked :count maintenance records complete. (:skipped skipped or already complete)',
];

<?php

return [

    'does_not_exist' => 'Lisans mevcut değil veya görüntüleme izniniz yok.',
    'user_does_not_exist' => 'Kullanıcı mevcut değil veya bunları görüntüleme izniniz yok.',
    'asset_does_not_exist' => 'Lisans ile ilişkilendirmek istediğiniz demirbaş mevcut değil.',
    'owner_doesnt_match_asset' => 'Lisans ile ilişkilendirmek istediğiniz demirbaş ilişkilendirmek istediğiniz kişiden başkasına atanmış durumda.',
    'assoc_users' => 'Bu demirbaş kullanıcıya çıkış yapılmış olaran görülüyor ve silinemez. Lütfen önce demirbaş girişi yapınız, ardından tekrar siliniz. ',
    'select_asset_or_person' => 'Bir varlık veya kullanıcı seçmelisiniz, ancak her ikisini birden değil.',
    'not_found' => 'Lisans bulunamadı',
    'seats_available' => ':seat_count atama yapılabilir',

    'create' => [
        'error' => 'Lisans oluşturulamadı, lütfen tekrar deneyin.',
        'success' => 'Lisans oluşturuldu.',
    ],

    'deletefile' => [
        'error' => 'Dosya silinemedi. Lütfen tekrar deneyin.',
        'success' => 'Dosya silindi.',
    ],

    'upload' => [
        'error' => 'Dosya(lar) yüklenemedi. Lütfen tekrar deneyin.',
        'success' => 'Dosya(lar) yüklendi.',
        'nofiles' => 'Yükleme için herhangi bir dosya seçmediniz veya karşıya yüklemeye çalıştığınız dosya çok büyük',
        'invalidfiles' => 'Bir veya daha fazla dosya çok büyük veya izin verilmeyen bir dosya türü. İzin verilen dosya türleri; png, gif, jpg, jpeg, doc, docx, pdf, txt, zip, rar, rtf, xml, and lic.',
    ],

    'update' => [
        'error' => 'Lisans güncellenemedi, lütfen tekrar deneyin',
        'success' => 'Lisans güncellendi.',
    ],

    'delete' => [
        'confirm' => 'Lisansı silmek istediğinize emin misiniz?',
        'error' => 'Lisansı silerken bir hata oluştu. Lütfen tekrar deneyin.',
        'success' => 'Lisans silindi.',
        'bulk_success' => 'The selected licenses were deleted successfully.',
        'partial_success' => 'License deleted successfully. See additional information below. | :count licenses were deleted successfully. See additional information below.',
        'bulk_checkout_warning' => ':license_name has seats that are currently checked out and cannot be deleted. Please check in all seats before deleting.',
    ],

    'checkout' => [
        'error' => 'Lisans çıkışı yapılırken hata oluştu. Lütfen tekrar deneyin.',
        'success' => 'Lisans çıkışı yapıldı.',
        'not_enough_seats' => 'Ödeme için yeterli sayıda lisans yeri yok',
        'mismatch' => 'Girdiğiniz bu lisans türü lisans ile eşleşmiyor',
        'unavailable' => 'Bu varlığı atayamazsınız.',
        'license_is_inactive' => 'Bu lisansın süresi dolmuş veya sonlandırılmıştır.',
    ],

    'checkin' => [
        'error' => 'Lisans girişi yapılırken hata oluştu. Lütfen tekrar deneyin.',
        'not_reassignable' => 'Lisans zaten kullanılmış',
        'success' => 'Lisans girişi yapıldı',
    ],

];

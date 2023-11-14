<?php 

return [
    'fileSingle' => 'fájl',
    'filePlural' => 'fájlok',
    'browseLabel' => 'Tallózás &hellip;',
    'removeLabel' => 'Eltávolítás',
    'removeTitle' => 'Kijelölt fájlok törlése',
    'cancelLabel' => 'Megszünteti',
    'cancelTitle' => 'Folyamatban lévő feltöltés megszakítása',
    'uploadLabel' => 'Feltöltés',
    'uploadTitle' => 'Kijelölt fájlok feltöltése',
    'msgNo' => 'Nem',
    'msgNoFilesSelected' => 'Nincsenek fájl kiválasztva',
    'msgCancelled' => 'Törölve',
    'msgPlaceholder' => 'Fájlok kiválasztása}...',
    'msgZoomModalHeading' => 'Részletes előnézet',
    'msgFileRequired' => 'Ki kell választania egy feltöltendő fájlt.',
    'msgSizeTooSmall' => 'A(z) "{name}" fájl (<b>{size} KB</b>) túl kicsi, és nagyobbnak kell lennie, mint <b>{minSize} KB</b>.',
    'msgSizeTooLarge' => 'A "{name}" fájl (<b>{size} KB</b>) meghaladja a maximálisan engedélyezett <b>{maxSize} KB</b> feltöltési méretet.',
    'msgFilesTooLess' => 'Legalább <b>{n}</b> {fájlt} ki kell választania a feltöltéshez.',
    'msgFilesTooMany' => 'A feltöltésre kiválasztott fájlok száma <b>({n})</b> meghaladja a <b>{m}</b> maximális megengedett korlátot.',
    'msgFileNotFound' => 'A(z) "{name}" fájl nem található!',
    'msgFileSecured' => 'A biztonsági korlátozások megakadályozzák a(z) "{name}" fájl olvasását.',
    'msgFileNotReadable' => 'A(z) "{name}" fájl nem olvasható.',
    'msgFilePreviewAborted' => 'A(z) "{name}" fájl előnézete megszakítva.',
    'msgFilePreviewError' => 'Hiba történt a(z) "{name}" fájl olvasása közben.',
    'msgInvalidFileName' => 'Érvénytelen vagy nem támogatott karakterek a(z) "{name}" fájlnévben.',
    'msgInvalidFileType' => 'Érvénytelen típus a(z) "{name}" fájlhoz. Csak a "{types}" fájlok támogatottak.',
    'msgInvalidFileExtension' => 'Érvénytelen kiterjesztése a(z) "{name}" fájlhoz. Csak a "{extensions}" fájlok támogatottak.',
    'msgFileTypes' => [
        'image' => 'kép',
        'html' => 'HTML',
        'text' => 'szöveg',
        'video' => 'videó',
        'audio' => 'hang',
        'flash' => 'vaku',
        'pdf' => 'PDF',
        'object' => 'tárgy',
    ],
    'msgUploadAborted' => 'A fájl feltöltése megszakadt',
    'msgUploadThreshold' => 'Feldolgozás...',
    'msgUploadBegin' => 'Inicializálás...',
    'msgUploadEnd' => 'Kész',
    'msgUploadEmpty' => 'Nem állnak rendelkezésre érvényes adatok a feltöltéshez.',
    'msgUploadError' => 'Hiba',
    'msgValidationError' => 'Validation Error',
    'msgLoading' => 'Fájl betöltése: {index}/{files} &hellip;',
    'msgProgress' => 'Fájl betöltése: {index}/{files} - {name} - {percent}% kész.',
    'msgSelected' => '{n} {fájl} kiválasztva',
    'msgFoldersNotAllowed' => 'Csak a fájlokat fogd át! {n} eldobott mappa kihagyva.',
    'msgImageWidthSmall' => 'A(z) "{name}" képfájl szélességének legalább {size} képpontnak kell lennie.',
    'msgImageHeightSmall' => 'A(z) "{name}" képfájl magasságának legalább {size} képpontnak kell lennie.',
    'msgImageWidthLarge' => 'A(z) "{name}" képfájl szélessége nem haladhatja meg a(z) {size} képpontot.',
    'msgImageHeightLarge' => 'A(z) "{name}" képfájl magassága nem haladhatja meg a(z) {size} px értéket.',
    'msgImageResizeError' => 'Nem sikerült átméretezni a kép méreteit.',
    'msgImageResizeException' => 'Hiba a kép átméretezése közben.<pre>{errors}</pre>',
    'msgAjaxError' => 'Valami hiba történt a(z) {operation} művelettel. Kérlek, próbáld újra később!',
    'msgAjaxProgressError' => '{operation} nem sikerült',
    'ajaxOperations' => [
        'deleteThumb' => 'fájl törlése',
        'uploadThumb' => 'fájlfeltöltés',
        'uploadBatch' => 'batch file upload',
        'uploadExtra' => 'űrlap adatfeltöltés',
    ],
    'dropZoneTitle' => 'Húzza ide a fájlokat &hellip;',
    'dropZoneClickTitle' => '<br>(vagy kattintson a(z) {files} kiválasztásához)',
    'fileActionSettings' => [
        'removeTitle' => 'Fájl eltávolítása',
        'uploadTitle' => 'Fájl feltöltés',
        'uploadRetryTitle' => 'Újra feltöltés',
        'downloadTitle' => 'Fájl letöltése',
        'zoomTitle' => 'Részletek megtekintése',
        'dragTitle' => 'Move / Rearrange',
        'indicatorNewTitle' => 'Még nincs feltöltve',
        'indicatorSuccessTitle' => 'feltöltve',
        'indicatorErrorTitle' => 'Feltöltési hiba',
        'indicatorLoadingTitle' => 'Feltöltés ...',
        'rotateTitle' => 'Rotate 90 deg. clockwise',
        'indicatorPausedTitle' => 'Upload Paused',
    ],
    'previewZoomButtonTitles' => [
        'prev' => 'Előző fájl megtekintése',
        'next' => 'Következő fájl megtekintése',
        'toggleheader' => 'Fejléc átváltása',
        'fullscreen' => 'Teljes képernyőre váltás',
        'borderless' => 'szegély nélküli mód váltása',
        'close' => 'Részletes előnézet bezárása',
        'rotate' => 'Rotate 90 deg. clockwise',
    ],
    'sizeUnits' => [
        '0' => 'B',
        '1' => 'KB',
        '2' => 'MB',
        '3' => 'GB',
        '4' => 'TB',
        '5' => 'PB',
        '6' => 'EB',
        '7' => 'ZB',
        '8' => 'YB',
    ],
    'bitRateUnits' => [
        '0' => 'B/s',
        '1' => 'KB/s',
        '2' => 'MB/s',
        '3' => 'GB/s',
        '4' => 'TB/s',
        '5' => 'PB/s',
        '6' => 'EB/s',
        '7' => 'ZB/s',
        '8' => 'YB/s',
    ],
    'pauseLabel' => 'Pause',
    'pauseTitle' => 'Pause ongoing upload',
    'msgPaused' => 'Paused',
    'msgTotalFilesTooMany' => 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
    'msgUploadResume' => 'Resuming upload &hellip;',
    'msgDeleteError' => 'Delete Error',
    'msgProgressError' => 'Error',
    'msgProcessing' => 'Processing ...',
    'msgDuplicateFile' => 'File "{name}" of same size "{size}" has already been selected earlier. Skipping duplicate selection.',
    'msgResumableUploadRetriesExceeded' => 'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
    'msgPendingTime' => '{time} remaining',
    'msgCalculatingTime' => 'calculating time remaining',
];

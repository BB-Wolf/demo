<?

use \Bitrix\Main\Localization\Loc;



$arResult['MODAL_NAME'] = 'modal-video';
$twigData = &$arResult['TEMPLATE_DATA']['reports'];
$twigData['title'][] = $arParams['TITLE'] ?: Loc::getMessage('REVIEWS');
$slides = &$twigData['slides'];

foreach ($arResult['ITEMS'] as $arItem) {
    $provider = '';
    $videoSRC = false;
    if ($arItem['PROPERTIES']['VIDEO_LINK']['VALUE']) {

        $video = $arItem['PROPERTIES']['VIDEO_LINK']['VALUE'];
        if (strpos(strtolower($video), 'youtube') !== false) {
            $provider = 'youtube';
            $videoSRC = substr($video, strpos($video, '=') + 1, strlen($video));
        }
    }

    if ($arItem['PROPERTIES']['FILE']['VALUE']) {
        $reviewType = Loc::getMessage('REVIEW_PDF');
    } else if ($videoSRC) {
        $reviewType = Loc::getMessage('REVIEW_VIDEO');
    } else {
        $reviewType = Loc::getMessage('REVIEW_PDF');
    }

    if ($arItem['ACTIVE_FROM']) {
        $reviewTime = $arItem['ACTIVE_FROM'];
        $date = new DateTime($reviewTime);
        $date = $date->format('Y-m-d');
    } else {
        $reviewTime = $arItem['TIMESTAMP_X'];
        $date = new DateTime($reviewTime);
        $date = $date->format('Y-m-d');
    }

    $pageHref = false;
    if ($videoSRC) {
        $pageHref =  false;
    } elseif ($arItem['PROPERTIES']['FILE']['VALUE']) {
        $pageHref = CFile::GetPath($arItem['PROPERTIES']['FILE']['VALUE']);
    } else {
        $pageHref  = false;
    }

    $slides[] = [
        "overlay" => true,
        'external' => $arItem['PROPERTIES']['FILE']['VALUE'] ? true : false,
        "videoIcon" => $videoSRC ? true : false,
        "video" => $videoSRC ? [
            "src" => $videoSRC,
            "provider" => $provider
        ] : false,
        "href" => $pageHref ?: false,
        'attr' => !$videoSRC && !$pageHref ? "data-modal-ajax='/backend/additionals.php?iblockID=" . REVIEWS_IBLOCK . "&elementID=" . $arItem['ID'] . "'" :
            false,
        "name" =>  false,
        "modal" =>  $videoSRC ? "video-modal" : false,
        "title" => $reviewType,
        "desc" => $arItem['NAME'],
        "image" => $arItem['PREVIEW_PICTURE']['SRC'] != '' ? [
            "src" => $arItem['PREVIEW_PICTURE']['SRC']
        ] : false,
        'date' => $date
    ];
}

$this->__component->setResultCacheKeys(['MODAL_NAME']);

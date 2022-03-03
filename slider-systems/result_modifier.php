<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */


$twigData = &$arResult['TEMPLATE_DATA']['slider_systems'];


if ($arResult['ITEMS'] && $arParams['SHOW_BLOCK'] == 'Y') {
    $twigData = [
        "title" => Loc::getMessage('ELECTRIC_SYSTEMS'),
        "numeric" => true
    ];

    foreach ($arResult['ITEMS'] as $arItem) {
        $twigData['slides'][] = [
            'id' => $arItem['CODE'] ?: $arItem['ID'],
            'title' => $arItem['NAME'],
            'image' => $arItem['PREVIEW_PICTURE'] ?
                [
                    'src' => $arItem['PREVIEW_PICTURE']['SRC'],
                    'alt' => $arItem['PREVIEW_PICTURE']['ALT'] ?: $arItem['NAME']
                ] : false,
            'href' => '#',
            'attr' => 'data-modal-ajax="/backend/additionals.php?iblockID=' . INFORMATION_CARDS_IN_POPUP_IBLOCK . '&additionalID=' . $arItem['PROPERTIES']['ADDITIONALS']['VALUE'] . '"'
        ];
    }
}

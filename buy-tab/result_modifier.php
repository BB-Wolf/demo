<?

use Bitrix\Main;
use \Bitrix\Main\Localization\Loc;

$twigData = &$arResult["TEMPLATE_DATA"]['offer'];
$section = $arResult['SECTION']['CODE'];

$twigData['title'][] = Loc::getMessage('BUY_N_SERVICE_TITLE');
$twigData['caption'] = Loc::getMessage('BUY_N_SERVICE_CAPTION');
foreach ($arResult['ITEMS'] as $arItem) {
    $arProps = $arItem["PROPERTIES"];

    $twigData["image"] = $arItem['PREVIEW_PICTURE']['SRC'] ? [
        "src" => $arItem['PREVIEW_PICTURE']['SRC'],
        "alt" => Loc::getMessage('BUY_N_SERVICE_TITLE')
    ] : false;
    $twigData['items'] =
        [
            [
                "text" =>   $arProps['PREPAID']['NAME'],
                "value" => $arProps['PREPAID']['VALUE']
            ],
            [
                "text" => $arProps['LEASE_TIME']['NAME'],
                "value" => $arProps['LEASE_TIME']['VALUE']
            ],
            [
                "text" => $arProps['LEASE_SUMM']['NAME'],
                "value" => $arProps['LEASE_SUMM']['VALUE']
            ]
        ];

    $twigData["content"] = [
        "title" => Loc::getMessage('MAIN_TERMS'),
        "text" => TruncateText($arItem['PREVIEW_TEXT'], 200)
    ];



    $twigData["action"] = [
        "text" => Loc::getMessage('GET_TERMS'),
        "title" => Loc::getMessage('GET_TERMS'),
        "attr" => "data-modal-ajax='/backend/lease.php?WEB_FORM_ID=" . LEASE_REQUEST_FORM . "'",
        "theme" => "gray",
        "icon" => [
            "name" => "16/arrow",
            "size" => "16"
        ],
        "fit" => true
    ];
}

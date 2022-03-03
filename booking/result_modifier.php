<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;

defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true || die();

CModule::IncludeModule('form');

if ($arParams['IBLOCK_TYPE'] == 'vip') {
    $textMessage = Loc::getMessage('BOOKING_TEXT_VIP');
} else {
    $textMessage = Loc::getMessage('BOOKING_TEXT_BL');
}

$arResult["TEMPLATE_DATA"]["block_feedback"] = [
    "heading" => [
        "h1" => $arParams['PAGE_TITLE'] ?: false
    ],
    "text"    => $textMessage,
    "form"    => [
        "header"     => '<input type="hidden" name="token" id="token" value="">',
        'hasHandler' => true,
        "action"     => '/backend/booking_lounge.php?iblockType=' . $arParams['IBLOCK_TYPE'],
        "method"     => "post",
        "name"       => "modal-form",
        "id"         => "modal-form-resume",
        "bottom"     => [
            "buttons" => [
                [
                    "type"    => "submit",
                    "theme"   => "blue-gradient",
                    "mob_fit" => true,
                    "text"    => Loc::getMessage('SEND_MESSAGE_BUTTON'),
                ]
            ],
        ],
    ],
];

$sPolicy = $arResult['arQuestions']['policy']['COMMENTS'];

$aForm = &$arResult["TEMPLATE_DATA"]["block_feedback"]["form"];
$aFormGroupFlight = &$aForm['group_fields'][];
$aFormGroupPassanger = &$aForm['group_fields'][];

$aFormGroupPassanger['markup_control'] = true;
$aFormGroupPassanger['label'] = Loc::getMessage('PASSANGER');

$aFormGroupFlight['label'] = Loc::getMessage('FLIGHT');
CForm::GetDataByID(
    BOOKING_FORM,
    $form,
    $questions,
    $answers,
    $dropdown,
    $multiselect
);

foreach ($questions as $question) {
    if ($question['SID'] !== 'age') {
        $aField = [
            'datepicker'  => $question['SID'] == 'departure_date' ? true : false,
            'timepicker'  => $question['SID'] == 'departure_time' ? true : false,
            'mode'        => $question['SID'] == 'departure_date' ? 'single' : '',
            "placeholder" => $question["TITLE"],
            'input'       => ($question['SID'] == 'departure_date' || $question['SID'] == 'departure_time') ? false : true,
            'radio'       => $question["SID"] !== 'age' ? false : true,
            'disabled'    => $question["SID"] !== 'departure_city' ? false : true,
            'full'        => ($question["SID"] === 'flight' || $question["SID"] === 'age') ? true : false,
            "name"        => $question['SID'] . '[]',
            'required'    => $question['REQUIRED'] == 'Y' ? true : false,
            "error"       => [
                "required" => Loc::getMessage('REQUIRED_FIELD'),
            ],
        ];
    } else {
        foreach ($answers['age'] as $questionKey => $questionValue) {
            $questionStructure[] = [
                'radio'        => true,
                'no_sign'      => true,
                'id'           => $questionValue['ID'],
                'name'         => 'RELATED_RATIO[]',
                'text'         => $questionValue['MESSAGE'],
                'checked'      => $questionValue['VALUE'] == 'grown' ? true : false,
                'value'        => $questionValue['MESSAGE'],
                'control_show' => $questionValue['VALUE'] == 'grown' ? false : true
            ];
            $aField['full'] = true;
            $aField['checklist'] = $questionStructure;
            $aField["control"] = 'birthday[]';
        }
    }

    if ($question['SID'] == 'birthday') {
        $aField["control_item"] = true;
        $aField["datepicker"] = true;
        $aField["mode"] = "single";
        $aField["placeholder"] = Loc::getMessage('BIRTHDAY_BOOKING');
        $aField["attr"] = "data-field-hidden";
        $aField["required"] = true;
        $aField['input'] = false;
    }

    if ($question['SID'] == 'phone') {
        $aField["full"] = true;
        $aField["type"] = "tel";
    }


    if (in_array($question["SID"], ['phone', 'age', 'surname', 'first_name', 'surname', 'birthday'])) {
        $aFormGroupPassanger['fields'][] = $aField;
    } else {
        $aFormGroupFlight['fields'][] = $aField;
    }
}

$aFormGroupPassanger['action_remove'] = ['text' => Loc::getMessage('DELETE_BUTTON')];

$aFormGroupPassanger['action_add'] = [
    "text"    => Loc::getMessage('ADD_PASSANGER'),
    "mob_fit" => true,
    "attr"    => "data-markup-control-add"
];

$arResult["TEMPLATE_DATA"]['modal'] = [
    'id'      => 'modal-form-message',
    "heading" => Loc::getMessage('MESSAGE_SUCCESS'),
    "text"    => Loc::getMessage('MESSAGE_SUCCESS_TEXT'),
    "timer"   => "5000",
    "actions" => [
        [
            "text"  => Loc::getMessage('MESSAGE_SUCCESS_BUTTON'),
            "theme" => "blue-gradient",
            'attr'  => 'data-modal-close'
        ]
    ],
];

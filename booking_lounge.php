<?php
include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Request;
use Bitrix\Main\Context;
use Bitrix\Loader;
use Bitrix\Main\Localization\Loc;

$request = Context::getCurrent()->getRequest();

if (!$request->isPost()) {
    die();
}

$token = $request->get('token');

if (!$token || !\GDZ\Local\Helper::checkRecaptcha($token)) {
    die();
}

$flight = $request->get('flight');
$departureDate = $request->get('departure_date');
$departureTime = $request->get('departure_time');
$arrivalCity = $request->get('arrival_city');
$iblockType = $request->get('iblockType');
$error = false;
$orderID = htmlspecialcharsEx($departureDate[0]) . ' - ' .
    htmlspecialcharsEx($departureTime[0]) . ' - ' .
    htmlspecialcharsEx($flight[0]) . ' - ' .
    htmlspecialcharsEx($arrivalCity[0]);

if ($iblockType === 'business') {
    $iblockID = BUSINESS_IBLOCK;
} elseif ($iblockType === 'vip') {
    $iblockID = VIP_IBLOCK;
} else {
    // так как у формы нет события error - просто используем die()
    die();
}

if (empty($departureDate) || empty($flight) || empty($request->get('surname')) || empty($request->get('phone'))) {
    // так как у формы нет события error - просто используем die()
    die();
}

$sectionFields = [
    'ACTIVE'    => 'Y',
    'IBLOCK_ID' => $iblockID,
    'NAME'      => $orderID,
];

$newSection = new CIBlockSection;
$newSectionAdd = $newSection->Add($sectionFields);
if (!$newSectionAdd) {
    $error = true;
}

$newElement = new CIBlockElement;
$elemFields = [];

$elemCounts = count($_REQUEST['surname']);

// так как из формы идут задублированные даты рождения - убираем лишние даты.
foreach ($_REQUEST['birthday'] as $birthdayKey => $birthdayVal) {
    if ($birthdayKey % 2 == 0) {
        unset($_REQUEST['birthday'][$birthdayKey]);
    }
}

$_REQUEST['birthday'] = array_values($_REQUEST['birthday']);

foreach ($_REQUEST['surname'] as $itemsKey => $itemsValue) {
    $elemFieldsProps[$itemsKey] = [];
    foreach ($_REQUEST as $key => $value) {
        if (is_array($value)) {
            $elemFieldsProps[$itemsKey][strtoupper($key)] = is_array($value[$itemsKey]) ? htmlspecialcharsEx($value[$itemsKey][$itemsKey]) : htmlspecialcharsEx($value[$itemsKey]);
        } else {
            $elemFieldsProps[$itemsKey][strtoupper($key)] = htmlspecialcharsEx($value);
        }

        if ($key == 'departure_date') {
            $elemFieldsProps[$itemsKey]['DEPARTURE_DATE'] = htmlspecialcharsEx($departureDate[0]);
        }

        if ($key == 'departure_time') {
            $elemFieldsProps[$itemsKey]['DEPARTURE_TIME'] = htmlspecialcharsEx($departureTime[0]);
        }

        if ($key == 'flight') {
            $elemFieldsProps[$itemsKey]['FLIGHT'] = htmlspecialcharsEx($flight[0]);
        }

        if ($key == 'arrival_city') {
            $elemFieldsProps[$itemsKey]['ARRIVAL_CITY'] = htmlspecialcharsEx($arrivalCity[0]);
        }
    }

    $elemFields['IBLOCK_ID'] = $iblockID;
    $elemFields['IBLOCK_SECTION_ID'] = $newSectionAdd;
    $elemFields['NAME'] = $elemFieldsProps[$itemsKey]['SURNAME'] . ' ' . $elemFieldsProps[$itemsKey]['FIRST_NAME'];
    $elemFields['PROPERTY_VALUES'] = $elemFieldsProps[$itemsKey];

    $newElementAdd = $newElement->Add($elemFields);
    if (!$newElementAdd) {
        $error = true;
    }
}

if (!$error) {
    $result['data'] = ['status' => 'success'];
    echo json_encode($result);
} else {
    $result['data'] = ['status' => 'error'];
    echo json_encode($result);
}

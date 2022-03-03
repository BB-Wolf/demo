<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */

if ($val = $arResult['MODAL_NAME']) {
    \Ast\Local\Common\Helpers\Modal::getInstance()->addModal($val);
    \Ast\Local\Common\Helpers\Modal::getInstance()->addModal('modal-ajax');
}

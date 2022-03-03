<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

$twigData = &$arResult['TEMPLATE_DATA']['modal'];

if ($arResult['ITEMS']) {

	$twigData['type'] = 'info';

	$slides = &$twigData['slider'];

	foreach ($arResult['ITEMS'] as $arItem) {
		$slides['slides'][] = [
			'title' => $arItem['NAME'],
			'text'  => $arItem['DETAIL_TEXT'] ?: $arItem['PREVIEW_TEXT'],
			'image' => $arItem['PREVIEW_PICTURE'] ?
				[
					'src'     => $arItem['PREVIEW_PICTURE']['SRC'],
					'alt'     => $arItem['PREVIEW_PICTURE']['DESCRIPTION'] ?: ($arItem['PREVIEW_PICTURE']['ALT'] ?: $arItem['NAME']),
					'overlay' => true
				] : false
		];
	}
}
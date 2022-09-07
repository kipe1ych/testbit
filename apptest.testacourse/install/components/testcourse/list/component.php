<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

\Bitrix\Main\Loader::includeModule("apptest.testacourse");
use Apptest\Testacourse\Coursetable;
use Bitrix\Main\Entity;

$limit = 10;
$offset = 0;
$sort = 'desc';
$sortField = 'ID';
if($arParams['COUNT']) $limit = $arParams['COUNT'];
if($_GET['PAGEN_1']) $offset = $_GET['PAGEN_1'] * $limit - $limit;
if($arParams['SORT_BY1']) $sortField = $arParams['SORT_BY1'];
if($arParams['SORT_ORDER1']) $sort = $arParams['SORT_ORDER1'];

$list = Coursetable::getList(
    array(
        "order"=>array(
            $sortField=>$sort,
        ),
        "limit"=>$limit,
        "offset"=>$offset
    )
);
while($arr = $list->fetch()) {
	$arResult["ELEMENTS"][] = $arr;
}
unset($arr);


$cnt = Coursetable::getCount();
$nav = new \Bitrix\Main\UI\ReversePageNavigation("page", $cnt);
$nav->allowAllRecords(true)->setPageSize($limit)->initFromUri();
$arResult["NAV_STRING"] = $nav;


$this->includeComponentTemplate();
return $arResult;
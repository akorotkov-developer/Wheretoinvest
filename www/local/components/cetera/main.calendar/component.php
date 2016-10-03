<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(empty($_REQUEST["CH_ID"]))
    CJSCore::Init(array('popup', 'date'));

$this->IncludeComponentTemplate();
?>
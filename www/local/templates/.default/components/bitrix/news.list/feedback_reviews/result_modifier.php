<?php
$rates = Array (
    "1" => Array (
        "COUNT" => 0,
        "PERCENT" => 0
    ),
    "2" => Array (
        "COUNT" => 0,
        "PERCENT" => 0
    ),
    "3" => Array (
        "COUNT" => 0,
        "PERCENT" => 0
    ),
    "4" => Array (
        "COUNT" => 0,
        "PERCENT" => 0
    ),
    "5" => Array (
        "COUNT" => 0,
        "PERCENT" => 0
    )
);
$ratesCount = 0;
$ratesSum = 0;

$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_REVIEW_RATING");
$arFilter = Array("IBLOCK_ID"=>9, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($arRes = $res->GetNext()) {
    $rate = $arRes["PROPERTY_REVIEW_RATING_VALUE"];
    if(isset($rate) && !empty($rate)) {
        $rates[$rate]["COUNT"]++;
        $ratesCount++;
    }
}

foreach($rates as $key => $val) {
    $percent = $rates[$key]["COUNT"] * 100 / $ratesCount;
    $rates[$key]["PERCENT"] = round($percent, 2, PHP_ROUND_HALF_UP);
    $ratesSum += $rates[$key]["COUNT"] * intval($key);
}

unset($res);

$arResult["RATES"] = $rates;
$arResult["RATES_COUNT"] = $ratesCount;
$arResult["RATES_AVERAGE"] = round($ratesSum/$ratesCount, 1, PHP_ROUND_HALF_UP);
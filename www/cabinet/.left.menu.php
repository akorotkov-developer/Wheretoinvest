<?
$aMenuLinks = Array(
    Array(
        "Настройки аккаунта",
        "/cabinet/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Мой регион",
        "/cabinet/region/",
        Array(),
        Array(),
        "!getContainer('User')->isPartner()"
    ),
    Array(
        "Моя методика определения надежности",
        "/cabinet/method/",
        Array(),
        Array(),
        "!getContainer('User')->isPartner()"
    ),
    Array(
        "Реквизиты организации",
        "/cabinet/details/",
        Array(),
        Array(),
        "getContainer('User')->isPartner()"
    ),
    Array(
        "Надежность",
        "",
        Array(),
        Array("class" => "accord__li_security"),
        "getContainer('User')->isPartner()"
    ),
    Array(
        "Рейтинги",
        "/cabinet/rating/",
        Array(),
        Array("class" => "accord__link_no-bord"),
        "getContainer('User')->isPartner()"
    ),
    Array(
        "Участие государства",
        "/cabinet/gov/",
        Array(),
        Array(),
        "getContainer('User')->isPartner()"
    ),
    Array(
        "Капитал и активы",
        "/cabinet/assets/",
        Array(),
        Array(),
        "getContainer('User')->isPartner()"
    ),
    Array(
        "Выйти",
        "?logout=Y",
        Array(),
        Array("class" => "show-for-small-only"),
        '$USER->IsAuthorized()'
    ),
);
?>
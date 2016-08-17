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
        "Данные пользователя",
        "/cabinet/info/",
        Array(),
        Array(),
        "!getContainer('User')->isPartner()"
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
        "Контакты",
        "/cabinet/info/",
        Array(),
        Array(),
        "getContainer('User')->isPartner()"
    ),
);
?>
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $USER;
if ($USER->IsAuthorized()):?>
    <?
    $userInfo = getContainer("User");
    $userName = $userInfo->isPartner() ? (!empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : (!empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : \CUser::FormatName("#NAME#", $userInfo, true))) : \CUser::FormatName("#NAME#", $userInfo, true);
    ?>
    <a href="/cabinet/" class="b-header__firstline-link" title="Личный кабинет"><?= trim($userName) ?></a>
    <span class="b-header__firstline-slash">|</span>
    <a href="?logout=yes" class="b-header__firstline-link">Выйти</a>
<? else: ?>
    <a href="/cabinet/auth/" class="b-header__firstline-link">Войти</a>
    <span class="b-header__firstline-slash">|</span>
    <a href="/cabinet/auth/?register=yes" class="b-header__firstline-link">Регистрация</a>
<? endif; ?>
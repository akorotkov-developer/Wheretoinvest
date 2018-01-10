<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $USER;
if ($USER->IsAuthorized()):?>
    <?
    $userInfo = getContainer("User");
    $userName = $userInfo->isPartner() ? (!empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : (!empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : \CUser::FormatName("#NAME#", $userInfo, true))) : \CUser::FormatName("#NAME#", $userInfo, true);
    $userShortName = $userInfo->isPartner() ? (!empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : (!empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : \CUser::FormatName("#NAME# #LAST_NAME#", $userInfo, true))) : \CUser::FormatName("#NAME# #LAST_NAME#", $userInfo, true);
    $shortName = "";
    $nameArr = explode(" ", $userShortName);
    foreach ($nameArr as $part) {
        $shortName .= trim(strtoupper(mb_substr($part, 0, 1)));
    }

    ?>
    <a href="/cabinet/" class="b-header__firstline-link" title="Личный кабинет">
        <span class="b-header__auth-link"><?= trim($userName) ?></span><span
            class="b-header__auth-link_short" title="<?= $userShortName ?>"><?= trim($shortName) ?></span></a>
    <span class="b-header__firstline-slash">|</span>
    <a href="?logout=yes" class="b-header__firstline-link">Выйти</a>
<? else: ?>
    <a href="/cabinet/auth/" class="b-header__firstline-link"><span
            class="b-header__auth-link">Войти</span><span
            class="b-header__auth-link_short"><img style="opacity:0.55;" src="/uploads/regicon_c_m.png"></span>
    </a>
    <span class="b-header__firstline-slash">|</span>
    <a href="/cabinet/auth/?register=yes" class="b-header__firstline-link">Регистрация&nbsp;<span
            data-tooltip
            aria-haspopup="true"
            class="has-tip assets__tooltip"
            title="Зарегистрированным пользователям доступны дополнительные возможности:<br><br>
            <ul>
                <li>cобственная методика определения надежности</li>
                <li>отсутствие рекламы</li>
                <li>управление e-mail подпиской</li>
            </ul>"></span></a>
<? endif; ?>
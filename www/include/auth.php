<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $USER;
if ($USER->IsAuthorized()):?>
    <span class="b-header__firstline-iconlogin"></span><!-- не убирать этот коммент
            --><a href="/cabinet/" class="b-header__firstline-link">Личный кабинет</a>
    <span class="b-header__firstline-slash">|</span>
    <a href="?logout=yes" class="b-header__firstline-link">Выйти</a>
<? else:?>
    <span class="b-header__firstline-iconlogin"></span><!-- не убирать этот коммент
            --><a href="/cabinet/auth/" class="b-header__firstline-link">Войти</a>
    <span class="b-header__firstline-slash">|</span>
    <a href="/cabinet/auth/?register=yes" class="b-header__firstline-link">Регистрация</a>
<? endif; ?>
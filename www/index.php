<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Куда вложить - помощь в выборе банка и вклада");
?>
<? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "",
        "PATH" => "/include/main_info.php",
        "AREA_FILE_RECURSIVE" => "Y",
        "EDIT_TEMPLATE" => "standard.php"
    )
); ?>
    <section class="b-sort row">
        <div class="b-sort__arr"></div>
        <div class="columns b-sort__all">100 000 000 ₽ на 1 год</div>
        <div class="b-sort__main">
            <div class="column medium-7">
                <span class="b-sort__label">Сумма:</span>
                <input type="text" class="b-sort__inp" value="10 000 000">
            </div>
            <div class="column small-5 medium-2 b-sort__select">
                <span class="b-sort__label">Валюта:</span>
                <select>
                    <option>руб.</option>
                    <option>евро</option>
                    <option>долл. США</option>
                </select>
            </div>
            <div class="column small-7 medium-3">
                <span class="b-sort__label">Срок:</span>
                <select>
                    <option>1 год</option>
                    <option>3 года и более</option>
                    <option>20 лет</option>
                </select>
            </div>

        </div>
    </section>
    <section class="b-offers ">
        <div class="row  b-offers__header small-only-text-center">

            <div class="column medium-4 small-4 ">
                <div class="b-offers__th first">
                    <a href="#" class="b-offers__title">
                        Организация
                    </a>
                </div>
            </div>
            <div class="column medium-2 hide-for-small-only">
                <div class="b-offers__th">
                    <a href="#" class="b-offers__title">
                        Способ вложения
                    </a>

                </div>
            </div>
            <div class="column medium-3 small-4 medium-text-right small-text-center b-offers__bility">
                <div class="b-offers__th b-offers__th_sort">
                    <a href="#" class="b-offers__title b-offers__title_sort">
                        Доходность
                    </a>

                </div>
            </div>
            <div class="column medium-2 small-4 medium-text-right small-text-center">
                <div class="b-offers__th b-offers__th_sort">
                    <a href="#" class="b-offers__title b-offers__title_sort">
                        Надежность
                    </a>

                </div>
            </div>
            <div class="column medium-1 show-for-medium-up">
                <div class="b-offers__th">&nbsp;</div>
            </div>
        </div>
        <div class="b-offers__item row">

            <div class="column medium-4  small-4 b-offers__firsttd">
                <div class="b-offers__logo">

                </div>
                <div class="b-offers__name">
                    Альфа банк
                </div>
                <div class="b-offers__arrows"></div>
            </div>

            <div class="column medium-2 hide-for-small-only">
                <div class="b-offers__type">
                    Банковский вклад
                </div>
            </div>
            <div class="column small-3 medium-3  text-right b-offers__profit b-offers__bility">
                <div class="b-offers__prof">999<span>%</span></div>
            </div>
            <div class="column small-5 medium-2  text-right">
                <div class="b-offers__prof">9999 <span>из 9999</span></div>
            </div>
            <div class="column hide-for-small-only medium-1 text-left  ">
                <div class="b-offers__stars"></div>
            </div>


            <div class="column small-12 b-offers__hidden">

                <div class="row b-offers__more">
                    <div class="column medium-1 show-for-medium-up">
                        &nbsp;
                    </div>
                    <div class="column medium-11">
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Наименование</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        Банковский вклад "Новогодний" с ежемесячной капитализацией процентов
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Рейтинг организации</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        Fitch BBB- <br>Moodys BB+<br>S&P BB+<br>РА Эксперт AAA+
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label b-offers__label_bot">Участие государства в капитале
                                    организации
                                </div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label b-offers__label_bot">Участие в системе страхования вкладов
                                </div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__img"><img src="./<?= WIC_TEMPLATE_PATH ?>/images/asb.jpg"
                                                                    alt=""></div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Капитал / Активы</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">28.5 <span>%</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Капитал</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">254 <span>млн. руб.</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Активы</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">2 500 <span>млн. руб.</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Организация</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        АО «АЛЬФА-БАНК», ОГРН 1027700067328, Лицензия ЦБ № 1326
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Обновлено</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        21.11.2016 в 16:13
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="columns show-for-small-only b-offers__best">
                        <div class="b-offers__liked">Избранное</div>
                        <div class="b-offers__stars"></div>
                    </div>
                    <div class="column medium-6 medium-offset-4 b-offers__go">
                        <a href="#" class="b-offers__link">Перейти на сайт</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="row b-offers__infl">
            <div class="columns medium-2 medium-offset-4 small-4 small-text-center medium-text-left">
                <div class="b-offers__type b-offers__type_infl">Инфляция</div>
            </div>
            <div class="columns medium-3 small-3 text-right end b-offers__percent b-offers__bility">
                <div class="b-offers__prof b-offers__prof_infl">11.5<span>%</span></div>
            </div>
        </div>
        <div class="b-offers__item row active">
            <div class="column medium-4  small-4 b-offers__firsttd">
                <div class="b-offers__logo">
                    <img src="<?= WIC_TEMPLATE_PATH ?>/images/logo1.png">
                </div>
                <div class="b-offers__name">
                    Альфа банк
                </div>
                <div class="b-offers__arrows"></div>
            </div>

            <div class="column medium-2 hide-for-small-only">
                <div class="b-offers__type">
                    Банковский вклад
                </div>
            </div>
            <div class="column small-3 medium-3  text-right b-offers__profit b-offers__bility">
                <div class="b-offers__prof">33.5<span>%</span></div>
            </div>
            <div class="column small-5 medium-2  text-right">
                <div class="b-offers__prof">36 <span>из 745</span></div>
            </div>
            <div class="column hide-for-small-only medium-1 text-left  ">
                <div class="b-offers__stars"></div>
            </div>

            <div class="column small-12 b-offers__hidden">

                <div class="row b-offers__more">
                    <div class="column medium-1 show-for-medium-up">
                        &nbsp;
                    </div>
                    <div class="column medium-11">
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Наименование</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        Банковский вклад "Новогодний" с ежемесячной капитализацией процентов
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Рейтинг организации</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        Fitch BBB- <br>Moodys BB+<br>S&P BB+<br>РА Эксперт AAA+
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label b-offers__label_bot">Участие государства в капитале
                                    организации
                                </div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label b-offers__label_bot">Участие в системе страхования вкладов
                                </div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__img"><img src="./<?= WIC_TEMPLATE_PATH ?>/images/asb.jpg"
                                                                    alt=""></div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Капитал / Активы</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">28.5 <span>%</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Капитал</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">254 <span>млн. руб.</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Активы</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__prof">2 500 <span>млн. руб.</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Организация</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        АО «АЛЬФА-БАНК», ОГРН 1027700067328, Лицензия ЦБ № 1326
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row b-offers__more-item">
                            <div class="column medium-3 small-6">
                                <div class="b-offers__label">Обновлено</div>

                            </div>
                            <div class="column medium-9 small-6 b-offers__nopadding">
                                <div class="b-offers__res2">
                                    <div class="b-offers__rest">
                                        21.11.2016 в 16:13
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="columns show-for-small-only b-offers__best">
                        <div class="b-offers__liked">Избранное</div>
                        <div class="b-offers__stars"></div>
                    </div>
                    <div class="column medium-6 medium-offset-4 b-offers__go">
                        <a href="#" class="b-offers__link">Перейти на сайт</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-offers__bottom">
            <a href="#" class="b-offers__showmore"><span>+</span>Показать ещё</a>
        </div>
    </section>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

    <?if($arResult["IS_PARTNER"]):


        if(!empty($arResult["FIELDS"])){?>
            <table class="results-table">
                <tbody><tr>
                    <th><a href="#" title="Сортировать">Название</a></th>
                    <th><a href="#" title="Сортировать">Для кого</a></th>

                    <th></th>
                </tr>
                <?foreach($arResult["FIELDS"] as $arKey=>$arItem){?>

                    <tr class="offers__row-1">

                        <td><?=$arItem["UF_NAME"]["VALUE"];?></td>
                        <td>
                            <?if($arItem["UF_FIZ"]["VALUE"]=="да"):?>

                            <i class="fa fa-user text-dark-gray"></i>&nbsp; Физ. лица
                            <?endif;?>
                            <?if($arItem["UF_URID"]["VALUE"]=="да"):?>

                                <i class="fa fa-building text-dark-gray"></i>&nbsp; Юр. лица
                            <?endif;?>

                        </td>

                        <td class="text-right"><a href="?offer_id=<?=$arItem["ID"]["VALUE"];?>" title="">Подробно &nbsp;<i class="fa fa-chevron-circle-right"></i></a></td>
                    </tr>

                <?}?>



                </tbody>
            </table>
        <?}else{?>
          <?=GetMessage("EMPTY_".$arParams["HIGHLOAD_ID"]);?>
        <?}?>


        <a href="?NEW=Y">Создать предложение</a>


    <?else:

        echo GetMessage("NOT_PARTNER");
    endif;?>
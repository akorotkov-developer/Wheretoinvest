<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

    <?if($arResult["IS_PARTNER"]):


        if(!empty($arResult["FIELDS"])){?>
            <table class="results-table">
                <tbody><tr>
                    <th><a href="#" title="Сортировать">Для кого</a></th>
                    <th><a href="#" title="Сортировать">Регион</a></th>
                    <th><a href="#" title="Сортировать">
                            Способ вложения
                            &nbsp;<i class="fa fa-chevron-down"></i></a></th>
                    <th><a href="#" title="Сортировать">Валюта</a></th>
                    <th></th>
                </tr>
                <?foreach($arResult["FIELDS"] as $arKey=>$arItem){?>

                    <tr class="offers__row-1">

                        <td>
                            <?if($arItem["UF_FIZ"]["VALUE"]=="да"):?>

                            <i class="fa fa-user text-dark-gray"></i>&nbsp; Физ. лица
                            <?endif;?>
                            <?if($arItem["UF_URID"]["VALUE"]=="да"):?>

                                <i class="fa fa-building text-dark-gray"></i>&nbsp; Юр. лица
                            <?endif;?>

                        </td>
                        <td><?=$arItem["UF_REGION"]["VALUE"];?></td>
                        <td><?=$arItem["UF_METHOD"]["VALUE"];?></td>
                        <td><?=$arItem["UF_CURRENCY"]["VALUE"];?></td>
                        <td class="text-right"><a href="?offer_id=<?=$arItem["ID"]["VALUE"];?>" title="">Подробно &nbsp;<i class="fa fa-chevron-circle-right"></i></a></td>
                    </tr>

                <?}?>



                </tbody>
            </table>
        <?}else{?>
          <?=GetMessage("EMPTY_".$arParams["HIGHLOAD_ID"]);?>
        <?}?>




    <?else:

        echo GetMessage("NOT_PARTNER");
    endif;?>
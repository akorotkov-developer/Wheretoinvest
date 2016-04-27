<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

    <?if($arResult["IS_PARTNER"]):


        if(!empty($arResult["FIELDS"])):

            foreach($arResult["FIELDS"] as $arKey=>$arItem):?>

                <?

                switch ($arItem["TYPE"]):
                    case "file":
                    ?>
                        <?if(!empty($arItem["VALUE"])):?>
                            <p><img src="<?=$arItem["VALUE"];?>"></p>
                        <?endif;?>


                    <?
                      break;
                    default:
                        if($arKey!="ID"):?>

                        <p><?=$arItem["TITLE"];?> : <?=$arItem["VALUE"];?></p>
                    <?
                        endif;
                    endswitch;
                ?>





            <?endforeach;?>

        <?else:?>
          <?=GetMessage("EMPTY_".$arParams["HIGHLOAD_ID"]);?>
        <?endif;?>


           <a href="?EDIT=Y">Изменить</a>



    <?else:

        echo GetMessage("NOT_PARTNER");
    endif;?>
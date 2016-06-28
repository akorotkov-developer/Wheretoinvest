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
            <?if($arResult["HBLOCK_ID"]==3 && !empty($arResult["MATRIX"])){?>
            <table>
                <?$countRows = count($arResult["MATRIX"][0]);?>
                <?$dates = array(
                    1 => "Дней",
                    2 => "Месяцев",
                    3 => "Лет"
                );?>
                <?foreach($arResult["MATRIX"] as $key=>$value){

                    echo "<tr>";

                    if($key==0)
                        echo "<td>&nbsp;</td>";

                    for ($i = 0; $i <= $countRows; $i++) {
                        if(!empty($arResult["MATRIX"][$key][$i])){
                            echo "<td>";

                            unset($valto);
                            if($key == 0){ //если это даты
                                echo $arResult["MATRIX"][$key][$i]["from"];


                                if(!empty($arResult["MATRIX"][$key][$i]["to"])){

                                    echo " - ";

                                    echo $arResult["MATRIX"][$key][$i]["to"];


                                }

                                echo " " . $dates[$arResult["MATRIX"][$key][$i]["date"]];


                            }elseif($i==0){
                                echo "от ";
                                echo $arResult["MATRIX"][$key][$i]["from"];



                                if(!empty($arResult["MATRIX"][$key][$i]["to"])){

                                    echo " до ";


                                    echo $arResult["MATRIX"][$key][$i]["to"];


                                }


                            } else {
                                echo $arResult["MATRIX"][$key][$i]." %";
                            }







                            echo "</td>";
                        }elseif($key !=0) {
                            echo "<td> ?% </td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>

            </table>
            <?}?>

            <a href="?offer_id=<?=$_REQUEST["offer_id"];?>&EDIT=Y">Изменить</a>

        <?else:?>
          <?=GetMessage("EMPTY_".$arParams["HIGHLOAD_ID"]);?>
        <?endif;?>






    <?else:

        echo GetMessage("NOT_PARTNER");
    endif;?>
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$randFunc = md5(rand(10000, 99999) . date("d.m.YH:i:s"));
?>
    <script type="text/javascript">
        function callbackFunction<?=$randFunc?>(data) {
            var date = new Date(data),
                currentDate = new Date();

            date.setHours(0);
            date.setMinutes(0);
            date.setSeconds(0);
            date.setMilliseconds(0);
            currentDate.setHours(0);
            currentDate.setMinutes(0);
            currentDate.setSeconds(0);
            currentDate.setMilliseconds(0);

            <?if(!empty($arParams["ONLY_PAST"])):?>
            if (date.getTime() > currentDate.getTime()) {
                alert("Укажите прошедшую дату");
                return false;
            }
            <?elseif(!empty($arParams["ONLY_FUTURE"])):?>
            if (date.getTime() < currentDate.getTime()) {
                alert("Укажите будущую дату");
                return false;
            }
            <?endif;?>
        }
    </script>
<?
if ($arParams['SILENT'] == 'Y') {
    return;
}

$cnt = strlen($arParams['INPUT_NAME_FINISH']) > 0 ? 2 : 1;

for ($i = 0; $i < $cnt; $i++):
    if ($arParams['SHOW_INPUT'] == 'Y'):
        ?><input type="text"
                 id="<?= $arParams['INPUT_NAME' . ($i == 1 ? '_FINISH' : '')] ?>"
                 name="<?= $arParams['INPUT_NAME' . ($i == 1 ? '_FINISH' : '')] ?>"
                 readonly
                 value="<?= $arParams['INPUT_VALUE' . ($i == 1 ? '_FINISH' : '')] ?>" <?= (Array_Key_Exists("~INPUT_ADDITIONAL_ATTR", $arParams)) ? $arParams["~INPUT_ADDITIONAL_ATTR"] : "" ?>/><?
    endif;
    ?>
    <a href="#" class="b-form__input_calendar-link"
       onclick="BX.calendar({node:this, field:'<?= htmlspecialcharsbx(CUtil::JSEscape($arParams['INPUT_NAME' . ($i == 1 ? '_FINISH' : '')])) ?>', form: '<? if ($arParams['FORM_NAME'] != '') {
           echo htmlspecialcharsbx(CUtil::JSEscape($arParams['FORM_NAME']));
       } ?>', value: <?= !empty($arParams['END_TIME']) ? $arParams['END_TIME'] : "''"; ?>, bTime: <?= $arParams['SHOW_TIME'] == 'Y' ? 'true' : 'false' ?>, currentTime: '<?= (time() + date("Z") + CTimeZone::GetOffset()) ?>', bHideTime: <?= $arParams['HIDE_TIMEBAR'] == 'Y' ? 'true' : 'false' ?>, callback: callbackFunction<?= $randFunc ?>}); return false;"
    ></a>
<? endfor; ?>
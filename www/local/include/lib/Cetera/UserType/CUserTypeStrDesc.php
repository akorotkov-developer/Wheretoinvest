<?
namespace Cetera\UserType;
    /**
     * usertypestr.php, Тип для пользовательских свойств - СТРОКА
     *
     * Содержит класс реализующий интерфейсы для типа "Строка".
     * @author Bitrix <support@bitrixsoft.com>
     * @version 1.0
     * @package usertype
     */

/**
 * Данный класс используется для управления экземпляром значения
 * пользовательского свойсва.
 *
 * <p>Некоторые методы этого класса заканчиваются на "2".
 * Они приведены для демонстрации и двойкой исключаются из процесса обработки.</p>
 * @package usertype
 * @subpackage classes
 */
class CUserTypeStrDesc extends \CUserTypeString
{
    /**
     * Обработчик события OnUserTypeBuildList.
     *
     * <p>Эта функция регистрируется в качестве обработчика события OnUserTypeBuildList.
     * Возвращает массив описывающий тип пользовательских свойств.</p>
     * <p>Элементы массива:</p>
     * <ul>
     * <li>USER_TYPE_ID - уникальный идентификатор
     * <li>CLASS_NAME - имя класса методы которого формируют поведение типа
     * <li>DESCRIPTION - описание для показа в интерфейсе (выпадающий список и т.п.)
     * <li>BASE_TYPE - базовый тип на котором будут основаны операции фильтра (int, double, string, date, datetime)
     * </ul>
     * @return array
     * @static
     */
    function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "strdesc",
            "CLASS_NAME" => "Cetera\\UserType\\CUserTypeStrDesc",
            "DESCRIPTION" => "Строка с описанием",
            "BASE_TYPE" => "string",
        );
    }

    /**
     * Эта функция вызывается при добавлении нового свойства.
     *
     * <p>Эта функция вызывается для конструирования SQL запроса
     * создания колонки для хранения не множественных значений свойства.</p>
     * <p>Значения множественных свойств хранятся не в строках, а столбиках (как в инфоблоках)
     * и тип такого поля в БД всегда text.</p>
     * @param array $arUserField Массив описывающий поле
     * @return string
     * @static
     */
    function GetDBColumnType($arUserField)
    {
        global $DB;
        switch (strtolower($DB->type)) {
            case "mysql":
                return "text";
            case "oracle":
                return "varchar2(2000 char)";
            case "mssql":
                return "varchar(2000)";
        }
    }

    /**
     * Эта функция вызывается перед сохранением метаданных свойства в БД.
     *
     * <p>Она должна "очистить" массив с настройками экземпляра типа свойства.
     * Для того что бы случайно/намеренно никто не записал туда всякой фигни.</p>
     * @param array $arUserField Массив описывающий поле. <b>Внимание!</b> это описание поля еще не сохранено в БД!
     * @return array Массив который в дальнейшем будет сериализован и сохранен в БД.
     * @static
     */
    function PrepareSettings($arUserField)
    {
        $size = intval($arUserField["SETTINGS"]["SIZE"]);
        $rows = intval($arUserField["SETTINGS"]["ROWS"]);
        $min = intval($arUserField["SETTINGS"]["MIN_LENGTH"]);
        $max = intval($arUserField["SETTINGS"]["MAX_LENGTH"]);

        return array(
            "SIZE" => ($size <= 1 ? 20 : ($size > 255 ? 225 : $size)),
            "ROWS" => ($rows <= 1 ? 1 : ($rows > 50 ? 50 : $rows)),
            "REGEXP" => $arUserField["SETTINGS"]["REGEXP"],
            "MIN_LENGTH" => $min,
            "MAX_LENGTH" => $max,
            "DEFAULT_VALUE" => $arUserField["SETTINGS"]["DEFAULT_VALUE"],
            "DESCRIPTION" => $arUserField["SETTINGS"]["DESCRIPTION"]
        );
    }

    /**
     * Эта функция вызывается при выводе формы редактирования значения свойства.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.
     * в форму редактирования сущности (на вкладке "Доп. свойства")</p>
     * <p>Элементы $arHtmlControl приведены к html безопасному виду.</p>
     * @param array $arUserField Массив описывающий поле.
     * @param array $arHtmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     * @return string HTML для вывода.
     * @static
     */
    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        preg_match("#\[(\d+)\]$#si", $arHtmlControl["NAME"], $match);
        $id = empty($match[1]) ? 0 : intval($match[1]);
        $value = is_array($arUserField["VALUE"][$id]) ? $arUserField["VALUE"][$id]["VALUE"] : $arUserField["VALUE"][$id];
        $desc = is_array($arUserField["VALUE"][$id]) ? $arUserField["VALUE"][$id]["DESCRIPTION"] : "";

        if ($arUserField["ENTITY_VALUE_ID"] < 1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"]) > 0)
            $arHtmlControl["VALUE"] = htmlspecialcharsbx($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
        if ($arUserField["SETTINGS"]["ROWS"] < 2) {
            $arHtmlControl["VALIGN"] = "middle";
            return '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[VALUE]" ' .
            'size="' . $arUserField["SETTINGS"]["SIZE"] . '" ' .
            ($arUserField["SETTINGS"]["MAX_LENGTH"] > 0 ? 'maxlength="' . $arUserField["SETTINGS"]["MAX_LENGTH"] . '" ' : '') .
            'value="' . $value . '" ' .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '> Описание: ' .
            '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[DESCRIPTION]" ' .
            'value="' . $desc . '" ' .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '>';
        } else {
            return '<textarea ' .
            'name="' . $arHtmlControl["NAME"] . '[VALUE]" ' .
            'cols="' . $arUserField["SETTINGS"]["SIZE"] . '" ' .
            'rows="' . $arUserField["SETTINGS"]["ROWS"] . '" ' .
            ($arUserField["SETTINGS"]["MAX_LENGTH"] > 0 ? 'maxlength="' . $arUserField["SETTINGS"]["MAX_LENGTH"] . '" ' : '') .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '>' . $value . '</textarea> Описание: ' .
            '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[DESCRIPTION]" ' .
            'value="' . $desc . '" ' .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '>';
        }
    }

    /**
     * Эта функция вызывается при выводе фильтра на странице списка.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $arHtmlControl приведены к html безопасному виду.</p>
     * @param array $arUserField Массив описывающий поле.
     * @param array $arHtmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     * @return string HTML для вывода.
     * @static
     */
    function GetFilterHTML($arUserField, $arHtmlControl)
    {
        return '<input type="text" ' .
        'name="' . $arHtmlControl["NAME"] . '" ' .
        'size="' . $arUserField["SETTINGS"]["SIZE"] . '" ' .
        'value="' . $arHtmlControl["VALUE"]["VALUE"] . '"' .
        '>';
    }

    /**
     * Эта функция вызывается при выводе значения свойства в списке элементов.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $arHtmlControl приведены к html безопасному виду.</p>
     * @param array $arUserField Массив описывающий поле.
     * @param array $arHtmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     * @return string HTML для вывода.
     * @static
     */
    function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {
        preg_match("#\[(\d+)\]$#si", $arHtmlControl["NAME"], $match);
        $id = empty($match[1]) ? 0 : intval($match[1]);
        $value = is_array($arUserField["VALUE"][$id]) ? $arUserField["VALUE"][$id]["VALUE"] : $arUserField["VALUE"][$id];

        if (strlen($value) > 0)
            return $value;
        else
            return '&nbsp;';
    }

    /**
     * Эта функция вызывается при выводе значения свойства в списке элементов в режиме <b>редактирования</b>.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $arHtmlControl приведены к html безопасному виду.</p>
     * @param array $arUserField Массив описывающий поле.
     * @param array $arHtmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     * @return string HTML для вывода.
     * @static
     */
    function GetAdminListEditHTML($arUserField, $arHtmlControl)
    {
        preg_match("#\[(\d+)\]$#si", $arHtmlControl["NAME"], $match);
        $id = empty($match[1]) ? 0 : intval($match[1]);
        $value = is_array($arUserField["VALUE"][$id]) ? $arUserField["VALUE"][$id]["VALUE"] : $arUserField["VALUE"][$id];
        $desc = is_array($arUserField["VALUE"][$id]) ? $arUserField["VALUE"][$id]["DESCRIPTION"] : "";

        if ($arUserField["SETTINGS"]["ROWS"] < 2)
            return '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[VALUE]" ' .
            'size="' . $arUserField["SETTINGS"]["SIZE"] . '" ' .
            ($arUserField["SETTINGS"]["MAX_LENGTH"] > 0 ? 'maxlength="' . $arUserField["SETTINGS"]["MAX_LENGTH"] . '" ' : '') .
            'value="' . $value . '" ' .
            '> Описание: ' .
            '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[DESCRIPTION]" ' .
            'value="' . $desc . '" ' .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '>';
        else
            return '<textarea ' .
            'name="' . $arHtmlControl["NAME"] . '[VALUE]" ' .
            'cols="' . $arUserField["SETTINGS"]["SIZE"] . '" ' .
            'rows="' . $arUserField["SETTINGS"]["ROWS"] . '" ' .
            ($arUserField["SETTINGS"]["MAX_LENGTH"] > 0 ? 'maxlength="' . $arUserField["SETTINGS"]["MAX_LENGTH"] . '" ' : '') .
            '>' . $value . '</textarea> Описание: ' .
            '<input type="text" ' .
            'name="' . $arHtmlControl["NAME"] . '[DESCRIPTION]" ' .
            'value="' . $desc . '" ' .
            ($arUserField["EDIT_IN_LIST"] != "Y" ? 'disabled="disabled" ' : '') .
            '>';
    }

    /**
     * Эта функция валидатор.
     *
     * <p>Вызывается из метода CheckFields объекта $USER_FIELD_MANAGER.</p>
     * <p>Который в свою очередь может быть вызван из меторов Add/Update сущности владельца свойств.</p>
     * <p>Выполняется 2 проверки:</p>
     * <ul>
     * <li>на минимальную длину (если в настройках минимальная длина больше 0).
     * <li>на регулярное выражение (если задано в настройках).
     * </ul>
     * @param array $arUserField Массив описывающий поле.
     * @param array $value значение для проверки на валидность
     * @return array массив массивов ("id","text") ошибок.
     * @static
     */
    function CheckFields($arUserField, $value)
    {
        $aMsg = array();
        $value = $value["VALUE"];
        if (strlen($value) < $arUserField["SETTINGS"]["MIN_LENGTH"]) {
            $aMsg[] = array(
                "id" => $arUserField["FIELD_NAME"],
                "text" => "Длина поля \"" . $arUserField["EDIT_FORM_LABEL"] . "\" должна быть не менее " . $arUserField["SETTINGS"]["MIN_LENGTH"] . " символов."
            );
        }
        if ($arUserField["SETTINGS"]["MAX_LENGTH"] > 0 && strlen($value) > $arUserField["SETTINGS"]["MAX_LENGTH"]) {
            $aMsg[] = array(
                "id" => $arUserField["FIELD_NAME"],
                "text" => "Длина поля \"" . $arUserField["EDIT_FORM_LABEL"] . "\" должна быть не более " . $arUserField["SETTINGS"]["MAX_LENGTH"] . " символов."
            );
        }
        if (strlen($arUserField["SETTINGS"]["REGEXP"]) > 0 && !preg_match($arUserField["SETTINGS"]["REGEXP"], $value)) {
            $aMsg[] = array(
                "id" => $arUserField["FIELD_NAME"],
                "text" => (strlen($arUserField["ERROR_MESSAGE"]) > 0 ?
                    $arUserField["ERROR_MESSAGE"] : "Поле \"" . $arUserField["EDIT_FORM_LABEL"] . "\" не удовлетворяет проверочному регулярному выражению."
                ),
            );
        }
        return $aMsg;
    }

    /**
     * Эта функция должна вернуть представление значения поля для поиска.
     *
     * <p>Вызывается из метода OnSearchIndex объекта $USER_FIELD_MANAGER.</p>
     * <p>Который в свою очередь вызывается и функции обновления поискового индекса сущности.</p>
     * <p>Для множественных значений поле VALUE - массив.</p>
     * @param array $arUserField Массив описывающий поле.
     * @return string посковое содержимое.
     * @static
     */
    function OnSearchIndex($arUserField)
    {
        $return = "";
        if (is_array($arUserField["VALUE"])) {
            foreach ($arUserField["VALUE"] as $val) {
                $return .= is_array($val["VALUE"]) ? implode("\r\n", $val["VALUE"]) : $val["VALUE"];
            }
        } else {
            $return .= is_array($arUserField["VALUE"]["VALUE"]) ? implode("\r\n", $arUserField["VALUE"]["VALUE"]) : $arUserField["VALUE"]["VALUE"];
        }
        return $return;
    }
}

?>
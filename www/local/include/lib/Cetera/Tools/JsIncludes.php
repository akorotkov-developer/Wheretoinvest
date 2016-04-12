<?php

namespace Cetera\Tools;

/**
 * Class JsIncludes
 * @author Sergey lebedev
 *
 * Класс для инклюда нужных js файлов на страницу. На разные страницы можно загружать разные наборы js файлов.
 * 1. Предположим, что у нас есть 4 файла
 * - jquery.js - jQuery, нужна на всех страницах
 * - common.js - общий для всего сайта
 * - catalog.js - для каталога
 * - order.js - для заказа
 * Соответственно, на страницах каталога нам не нужен order.js, на странице заказа - catalog.js. common.js нужен везде на сайте
 *
 * 2. Зарегистрируем наши файлы в массиве \JsIncludes::$files с помощью функции JsIncludes::registerFile('common.common', '#SITE_TEMPLATE_PATH#/js/common.js?v=1')
 * Ключ - [название модуля].[название подмодуля].[алиас файла]. Модуль - условное обозначение для какой-то группы js файлов, например catalog, personal. Подмодуль - более детальное деление, например catalog.catalog, catalog.cart, catalog.order. Алиас файла - конечный файл.
 * Значение - путь до файла. Поддерживаются "переменные": #SITE_TEMPLATE_PATH# - трансформируется в путь до текущего шаблона.
 * Свои "переменные" можно добавлять через JsIncludes::injectVariable('#CETERA_TEMPLATE_PATH#', '/local/нужный/нам/путь/до/файлов');
 * таким образом в массив \JsIncludes::$files мы добавим примерно следующие записи:
 * 'common.jquery' => '#SITE_TEMPLATE_PATH#/js/jquery.js?v=2.1.1',
 * 'common.common' => '#SITE_TEMPLATE_PATH#/js/common.js?v=1',
 * 'catalog.catalog' => '#SITE_TEMPLATE_PATH#/js/catalog/catalog.js?v=1',
 * 'catalog.order' => '#SITE_TEMPLATE_PATH#/js/catalog/order/.js?v=1',
 * Почему не
 * 'catalog => '#SITE_TEMPLATE_PATH#/js/catalog/catalog.js?v=1',
 * 'order' => '#SITE_TEMPLATE_PATH#/js/catalog/order/.js?v=1',
 * Потому что, бывает удобно включить на страницу сразу целый Модуль (весь набор файлов). Сделать это можно будет обратившись по имени 'catalog' вместо 'catalog, order'. Да, запись 'catalog.catalog, catalog.order' тоже возможна.
 * добавив данные записи в массив мы сказали классу, что он может подключать такие-то вот файлы по такому-то пути
 *
 * 3. Теперь надо подключить на нужной странице сайта нужные нам файлы, для этого делаем:
 * JsIncludes::includeFiles('catalog.order');
 * Помещать данный код удобно в component_epilog.php
 * Общий скрипт подключим в футере шаблона footer.php:
 * JsIncludes::includeFiles('common'); # подключили весь модуль
 * мы могли то же самое написать как:
 * JsIncludes::includeFiles('common.jquery, common.common'); // подключили файлы по отдельности
 * или
 * JsIncludes::includeFiles(array('common.jquery', 'common.common'); // подключили файлы по отдельности
 *
 * 4. Осталось вывести на страницу все подключенные нами скрипты для данной страницы. Делаем это в футере (ближе к закрывающему body):
 * echo JsIncludes::showIncludes();
 *
 * 5. На продакшене можно собирать все файлы в один. Для этого пишем:
 * JsIncludes::compressFiles();
 * echo JsIncludes::showIncludes();
 *
 * Логика перегенерации сжатого файла простая - изменили путь до файла (значение элемента в массиве \JsIncludes::$files) - обновился файл. Для этого и понадобится "....js?v=1" . Меняем v=1 на v=2 и сжатый файл обновляется.
 * На develop версии удобно не сжимать файл
 */
class JsIncludes
{
    /**
     * @var array array(
     * 'moduleName.fileAlias' => 'path/to/file',
     * 'moduleName.file2' => 'path/to/file2',
     * 'moduleName.subModule.file1' => 'path/to2/file3',
     * 'moduleName.subModule.subModule2.file1' => 'path/to2/file4',
     * 'moduleName2.file1' => 'path/to3/file1',
     * )
     */
    private static $files = array(
        'common.jq' => 'http://yandex.st/jquery/1.11.1/jquery.min.js',
        'common.common' => '#SITE_TEMPLATE_PATH#/js/common.js?v=1',
    );

    private static $injectedVariables = array('#SITE_TEMPLATE_PATH#' => SITE_TEMPLATE_PATH);

    const COMPRESSED_FILES_PATH = '/bitrix/cache/js/jsincludes/';
    const COMPRESSED_TAG = 'COMPRESSED';

    const MODULE_DELIMITER = '.';

    /**
     * @var array подключенные файлы
     */
    private static $includedFiles = array();

    /**
     * Подключение файлов
     * @param $files array|string "moduleName, moduleName2.submodule.file, file5, moduleName2.file2" OR array('moduleName', 'moduleName2.submodule.file', 'file5', 'moduleName2.file2')
     * @return bool
     */
    public static function includeFiles($files)
    {
        if (is_string($files) && strlen($files))
        {
            $files = explode(',', $files);
        }

        if (is_array($files) && !empty($files))
        {
            foreach ($files as $file)
            {
                $file = trim($file);
                if (!strlen($file))
                    continue;

                if (self::isFileAliasExists($file))
                    self::$includedFiles[$file] = $file;
            }

            return true;
        }

        return false;
    }

    /**
     * импортировать файл в инклюды
     * @param $fileAlias
     * @param $filePath
     * @return bool false в случае, если файл с таким alias уже есть, true при успешном добавлении файла
     */
    public static function importFile($fileAlias, $filePath)
    {
        if (self::isFileAliasExists($fileAlias))
            return false;

        self::$files[$fileAlias] = $filePath;

        return true;
    }

    private static function isFileAliasExists($fileAlias)
    {
        static $filesKeys = null;

        if (is_null($filesKeys))
            $filesKeys = array_keys(self::$files);

        foreach ($filesKeys as $fa)
        {
            // ищем целое название article.edit не подходит для article.edits.file1
            if (strpos($fa, $fileAlias) === 0 && ($fa == $fileAlias || substr($fa, strlen($fileAlias), 1) == self::MODULE_DELIMITER))
                return true;
        }

        return false;
    }


    public static function showIncludes()
    {
        $filesToShow = array();
        $toShowStr = '';

        if (empty(self::$includedFiles))
            return $toShowStr;

        foreach (self::$includedFiles as $incAlias => $incFile)
        {
            if ($incAlias == self::COMPRESSED_TAG)
            {
                $filesToShow[self::COMPRESSED_TAG] = $incFile;
                continue;
            }

            foreach (self::$files as $fileAlias => $file)
            {
                if (strpos($fileAlias, $incFile) === 0 && ($fileAlias == $incFile || substr($fileAlias, strlen($incFile), 1) == self::MODULE_DELIMITER))
                {
                    // перезаписать данный файл, тогда он будет ниже в массиве.
                    if (isset($filesToShow[$file]))
                        unset($filesToShow[$file]);

                    $filesToShow[$file] = self::replaceTpl($file);
                }
            }
        }

        if (!empty($filesToShow))
            foreach ($filesToShow as $fileToShow)
                $toShowStr .= "<script src=\"" . $fileToShow . "\" type=\"text/javascript\"></script>\n";

        return $toShowStr;
    }


    private static function replaceTpl($str)
    {
        return str_replace(array_keys(self::$injectedVariables), self::$injectedVariables, $str);
    }

    /**
     * Добавляет переменную для замены
     * @param $template '#TEMPLATE#'
     * @param $value $value
     */
    public static function injectVariable($template, $value)
    {
        self::$injectedVariables[$template] = $value;
    }

    /**
     * Регистрирует JS файл для последующего импользования
     * @param $key 'common.common'
     * @param $value '#SITE_TEMPLATE_PATH#/js/common.js?v=1'
     */
    public static function registerFile($key, $value)
    {
        self::$files[$key] = $value;
    }

    /**
     * Сжатие файлов в один
     * @return bool
     */
    public static function compressFiles()
    {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . self::COMPRESSED_FILES_PATH . '#HASH#.js';

        if (empty(self::$includedFiles))
            return true;

        $filesToShow = $fileHash = array();

        foreach (self::$includedFiles as $incFile)
        {
            foreach (self::$files as $fileAlias => $file)
            {
                if (strpos($fileAlias, $incFile) === 0 && ($fileAlias == $incFile || substr($fileAlias, strlen($incFile), 1) == self::MODULE_DELIMITER))
                {
                    // перезаписать данный файл, тогда он будет ниже в массиве.
                    if (isset($filesToShow[$file]))
                        unset($filesToShow[$file]);

                    $filesToShow[$file] = self::replaceTpl($file);
                    $fileHash[] = $filesToShow[$file];
                }
            }
        }

        if (empty($filesToShow))
            return true;

        // название сжатого файла
        $fileHash = md5(implode('|', $fileHash));

        // путь до сжатого файла
        $compressedFile = str_replace('#HASH#', $fileHash, $fullPath);

        if (is_readable($compressedFile))
        {
            self::$includedFiles = array(
                self::COMPRESSED_TAG => self::COMPRESSED_FILES_PATH . $fileHash . '.js',
            );

            return true;
        } else
        {
            // создать папку для хранения файлов, если отсутствует
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . self::COMPRESSED_FILES_PATH))
                if (!mkdir($_SERVER['DOCUMENT_ROOT'] . self::COMPRESSED_FILES_PATH, 0777, true))
                {
                    return false;
                }

            if (file_put_contents($compressedFile, ';') === false)
            {
                return false;
            }
        }


        foreach ($filesToShow as $fileToShow)
        {

            $fileToShow = preg_replace('~^(.+?)(\?.*)~', "$1", $fileToShow);

            if (($fileContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $fileToShow)) === false)
            {
                return false;
            }

            $fileInfo = pathinfo($fileToShow);

            $fileContent = ";\n//# sourceURL={$fileInfo['basename']}\n" . $fileContent;

            if (file_put_contents($compressedFile, $fileContent, FILE_APPEND) === false)
            {
                return false;
            }
        }

        self::$includedFiles = array(
            self::COMPRESSED_TAG => self::COMPRESSED_FILES_PATH . $fileHash . '.js',
        );

        return true;
    }

}
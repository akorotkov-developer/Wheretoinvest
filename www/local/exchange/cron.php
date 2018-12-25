<?
use Wic\BanksInfo\UpdateBanks,
    Wic\BanksInfo\Tools,
    Wic\BanksInfo\Info,
    Wic\BanksInfo\SiteOffers;

$exit_code = 0;

try {

    @set_time_limit(0);
    @ignore_user_abort(true);
    @error_reporting(E_ERROR);

    @ini_set('mbstring.func_overload', '2');
    @ini_set('mbstring.internal_encoding', 'UTF-8');

    define("NO_KEEP_STATISTIC", true);
    define("NOT_CHECK_PERMISSIONS", true);
    define('CHK_EVENT', true);
    define('BX_NO_ACCELERATOR_RESET', true);


    $is_console = PHP_SAPI === 'cli' || (!isset($_SERVER['DOCUMENT_ROOT']) && !isset($_SERVER['REQUEST_URI']));

    if($is_console)
        $_SERVER['DOCUMENT_ROOT'] = '/var/www/wheretoinvest.ru/www';

    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."../logs/log.txt");

    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

    if(!$is_console && !$GLOBALS['USER']->IsAdmin())
        throw new Exception('Restricted access');

    $GLOBALS['DB']->Query("SET wait_timeout=28800");


    $banks = new UpdateBanks;
    $banks->updateUsers($Cinfo = new Info ,   $siteOffers = new SiteOffers);
    unset($banks);

}
catch (\Exception $e) {
    $exit_code = 1;
    //Import::logData($e->getMessage(), $_SERVER['DOCUMENT_ROOT'] . '/../logs/custom/import/errors.log');
}

if ($exit_code > 0) {
    http_response_code(500);
}
exit ($exit_code);
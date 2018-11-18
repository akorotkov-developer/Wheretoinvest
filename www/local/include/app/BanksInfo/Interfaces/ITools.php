<?
namespace Wic\BanksInfo\Interfaces;

interface ITools
{
    public static function translit($s, $param);
    public static function getShortNameByFullName($FullName);
}
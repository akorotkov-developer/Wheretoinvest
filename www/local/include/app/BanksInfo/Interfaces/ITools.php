<?
namespace Wic\BanksInfo\Interfaces;

interface ITools
{
    public function translit($s, $param);
    public function getShortNameByFullName($FullName);
}
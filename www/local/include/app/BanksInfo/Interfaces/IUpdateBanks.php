<?
namespace Wic\BanksInfo\Interfaces;

use Wic\BanksInfo\Tools, Wic\BanksInfo\Info, Wic\BanksInfo\SiteOffers;

interface IUpdateBanks
{
    public function updateUsers(Tools $tools, Info $info,  SiteOffers $siteOffers);
}
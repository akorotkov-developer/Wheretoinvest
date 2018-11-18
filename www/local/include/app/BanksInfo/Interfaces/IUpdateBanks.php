<?
namespace Wic\BanksInfo\Interfaces;

use Wic\BanksInfo\Info, Wic\BanksInfo\SiteOffers;

interface IUpdateBanks
{
    public function updateUsers(Info $info,  SiteOffers $siteOffers);
}
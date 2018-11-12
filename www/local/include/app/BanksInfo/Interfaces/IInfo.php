<?
namespace Wic\BanksInfo\Interfaces;

use Wic\BanksInfo\Tools;

interface IInfo
{
    public function getWebSiteOrganization($BIC);
    public function get135formData($regNumber);
    public function get123formData($regNumber);
    public function getOrgInfoByIntCode($BIC, Tools $tools);
    public function getFilialInfoByBic($BIC);
}
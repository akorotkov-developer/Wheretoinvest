<?
namespace Wic\BanksInfo;

class SiteOffers implements Interfaces\ISiteOffers {

    //Добавлить предложение и Матрицу для банка
    public function setOfferAndMAtrix($userID) {
        $itemHblockOffer = new \Cetera\HBlock\SimpleHblockObject(Config::HOFFRES);
        $itemHblockMatrix = new \Cetera\HBlock\SimpleHblockObject(Config::HMATRIX);

        //Проверяем есть ли предлжение с таким ID
        $idElem = array();
        $filter["UF_USER_ID"] = $userID;
        $query["filter"] = $filter;
        $list = $itemHblockOffer->getList($query);
        while ($el = $list->fetch()) {
            $idElem[] = $el;
        }

        //TODO пока клиент не придумал что делать с полученными данными берем первое попавшееся предложение
        $offerID = $idElem[0]["ID"];
        $findMatrix = false;

        if ($offerID) {
            //Если предложение есть, то ищем Матрицу в HBlock Matrix создаем Матрицу в HBlock Matrix
            $filter = array();
            $filter["UF_OFFER"] = $offerID;
            $query["filter"] = $filter;
            $list = $itemHblockMatrix->getList($query);
            $Elem = false;
            while ($el = $list->fetch()) {
                $Elem[] = $el;
            }
            //TODO Если нашли Матрицу в HBlock Matrix то..
            if ($Elem) {
                $findMatrix = true;
            }
        } else {
            //Если нет создаем предложение
            $date = Config::ENDDATEOFFER;
            $date = strtotime($date); // переводит из строки в дату
            $dateEnd = date("d.m.Y", $date);

            $offeritems = array(
                "UF_USER_ID"=>$userID,
                "UF_METHOD"=>Config::OFFER_UF_METHOD,
                "UF_NAME"=>"-",
                "UF_TYPE"=>Config::OFFER_UF_TYPE,
                "UF_REGIONS"=>Config::ALL_REGIONS,
                "UF_UPDATED"=>date("d.m.Y H:i:s"),
                "UF_SITE"=>"",
                "UF_ACTIVE_START"=>array(date("d.m.Y")),
                "UF_ACTIVE_END"=>array($dateEnd)
            );
            $offerID = $itemHblockOffer->add($offeritems)->getId();
        }

        if (!$findMatrix) {
            $item = array(
                "UF_OFFER" => $offerID,
                "UF_SUMM" => Config::MATRIX_UF_SUMM,
                "UF_DATE_START" => Config::MATRIX_UF_DATE_START,
                "UF_CURRENCY" => Config::MATRIX_UF_CURRENCY,
                "UF_PERCENT" => Config::MATRIX_UF_PERCENT
            );
            $itemHblockMatrix->add($item);
        }

    }
}
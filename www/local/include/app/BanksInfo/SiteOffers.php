<?
namespace Wic\BanksInfo;

class SiteOffers implements Interfaces\ISiteOffers {
    const ALL_REGIONS = array(71,77,24,34,3,16,18,32,23,17,83,66,4,64,38,26,5,78,42,67,47,6,31,65,85,60,7,20,8,79,19,2,25,29,48,28,68,69,54,9,53,52,81,27,35,75,55,72,37,41,36,22,21,46,44,80,39,45,74,73,33,10,51,30,57,82,59,4913,11,40,12,13,70,14,58,49,56,76,62,63,43,50,84,61,15);

    //Добавлить предложение и Матрицу для банка
    public function setOfferAndMAtrix($userID) {
        $itemHblockOffer = new \Cetera\HBlock\SimpleHblockObject(3);
        $itemHblockMatrix = new \Cetera\HBlock\SimpleHblockObject(9);

        //Проверяем есть ли предлжение с таким ID
        $idElem = array();
        $filter["UF_USER_ID"] = $userID;
        $query["filter"] = $filter;
        $list = $itemHblockOffer->getList($query);
        while ($el = $list->fetch()) {
            $idElem[] = $el;
        }

        //TODO пока клиент не придумал что делать с полученными данными берем первое попавшееся предложение
        if ($idElem[0]["ID"]) {
            //Если предложение есть, то ищем Матрицу в HBlock Matrix создаем Матрицу в HBlock Matrix
            $filter = array();
            $filter["UF_OFFER"] = $idElem[0]["ID"];
            $query["filter"] = $filter;
            $list = $itemHblockMatrix->getList($query);
            $Elem = false;
            while ($el = $list->fetch()) {
                $Elem[] = $el;
            }
            //TODO Если нашли Матрицу в HBlock Matrix то..
            if ($Elem) {
                //В процессе
            } else {
                $item = array(
                    "UF_OFFER" => $idElem[0]["ID"],
                    "UF_SUMM"  => 10000,
                    "UF_DATE_START" => 369,
                    "UF_CURRENCY" => 28,
                    "UF_PERCENT" => 0.1
                );
                $itemHblockMatrix->add($item);
            }
        } else {
            //Если нет создаем предложение
            $date = "12.10.2019";
            $date = strtotime($date); // переводит из строки в дату
            $dateEnd = date("d.m.Y", $date);

            $item = array(
                "UF_USER_ID"=>$userID,
                "UF_METHOD"=>3,
                "UF_NAME"=>"-",
                "UF_TYPE"=>26,
                "UF_REGIONS"=>self::ALL_REGIONS,
                "UF_UPDATED"=>date("d.m.Y H:i:s"),
                "UF_SITE"=>"",
                "UF_ACTIVE_START"=>array(date("d.m.Y")),
                "UF_ACTIVE_END"=>array($dateEnd)
            );
            $itemHblockOffer->add($item);

            //Ищем предложение
            $idElem =array();
            $filter["UF_USER_ID"] = $userID;
            $query["filter"] = $filter;
            $list = $itemHblockOffer->getList($query);
            while ($el = $list->fetch()) {
                $idElem[] = $el;
            }
            //Затем создаем матрицу для этого предложения
            if ($idElem[0]["ID"]) {
                $item = array(
                    "UF_OFFER" => $idElem[0]["ID"],
                    "UF_SUMM"  => 10000,
                    "UF_DATE_START" => 369,
                    "UF_CURRENCY" => 28,
                    "UF_PERCENT" => 0.1
                );
                $itemHblockMatrix->add($item);
            }
        }
    }
}
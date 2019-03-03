<?
namespace Wic\BanksInfo;

class Tools implements Interfaces\ITools {

    public static function translit($s, $param = "N") {
        if ($param == "Y") {
            $s = (string)$s; // преобразуем в строковое значение
            $s = strip_tags($s); // убираем HTML-теги
            $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
            $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
            $s = trim($s); // убираем пробелы в начале и конце строки
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
            $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
            $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
            $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
            return $s; // возвращаем результат
        } else {
            $translit = array(
                'а' => 'a',   'б' => 'b',   'в' => 'v',

                'г' => 'g',   'д' => 'd',   'е' => 'e',

                'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',

                'и' => 'i',   'й' => 'j',   'к' => 'k',

                'л' => 'l',   'м' => 'm',   'н' => 'n',

                'о' => 'o',   'п' => 'p',   'р' => 'r',

                'с' => 's',   'т' => 't',   'у' => 'u',

                'ф' => 'f',   'х' => 'x',   'ц' => 'c',

                'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',

                'ь' => '\'',  'ы' => 'y',   'ъ' => '\'\'',

                'э' => 'e\'',   'ю' => 'yu',  'я' => 'ya',


                'А' => 'A',   'Б' => 'B',   'В' => 'V',

                'Г' => 'G',   'Д' => 'D',   'Е' => 'E',

                'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',

                'И' => 'I',   'Й' => 'J',   'К' => 'K',

                'Л' => 'L',   'М' => 'M',   'Н' => 'N',

                'О' => 'O',   'П' => 'P',   'Р' => 'R',

                'С' => 'S',   'Т' => 'T',   'У' => 'U',

                'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',

                'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',

                'Ь' => '\'',  'Ы' => 'Y\'',   'Ъ' => '\'\'',

                'Э' => 'E\'',   'Ю' => 'YU',  'Я' => 'YA',
            );

            $string = strtr($s, $translit);
            return $string;
        }
    }

    public static function getShortNameByFullName($FullName) {
        $name = $FullName;
        $ShortName = "";
        $needle = '"';
        $lastPos = 0;
        $positions = array();
        if (strpos($name, $needle, $lastPos) == false) {
            $needle = "(";
            while (($lastPos = strpos($name, $needle, $lastPos))!== false) {
                $positions[] = $lastPos;
                $lastPos = $lastPos + strlen($needle);
            }

            $needle = ")";
            while (($lastPos = strpos($name, $needle, $lastPos))!== false) {
                $positions[] = $lastPos;
                $lastPos = $lastPos + strlen($needle);
            }

            $cuurentN = substr($name, $positions[0], $positions[count($positions)-1]-$positions[0]+1);


            if ($positions[0] == 0) {
                $firstN = false;
            } else {
                $firstN = substr($name, 0, $positions[0] - 1);
            }

            $cuurentN = explode(" ", $cuurentN);

            foreach ($cuurentN as $word ) {
                if ($word[0] == "(") {
                    $ShortName .= substr($word, 1, 1);
                } else {
                    if ($word[count($word) - 1] == ")") { $word[count($word) - 1] = "";}
                    $ShortName .=  substr($word, 0, 1);
                }
            }

            $BeginName =  substr($name, 0, $positions[0] );

            $ShortName =$BeginName."(".strtoupper($ShortName).")";

        } else {

            while (($lastPos = strpos($name, $needle, $lastPos)) !== false) {
                $positions[] = $lastPos;
                $lastPos = $lastPos + strlen($needle);
            }

            $cuurentN = substr($name, $positions[0], $positions[count($positions) - 1] - $positions[0] + 1);

            if ($positions[0] == 0) {
                $firstN = false;
            } else {
                $firstN = substr($name, 0, $positions[0] - 1);
            }
            $lastN = substr($name, $positions[count($positions) - 1] + 2, strlen($name) - 1);


            if ($firstN) {
                $firstN = explode(' ', $firstN);
                if (count($firstN) > 1) {
                    foreach ($firstN as $i => $element) {
                        if (strlen($firstN[$i]) > 1) {
                            $fSymb = substr($firstN[$i], 0, 1);
                            if ($fSymb != '"') {
                                if ($fSymb == '(') {
                                    $fSymb = substr($firstN[$i], 1, 1);
                                    $firstN[$i] = '(' . strtoupper($fSymb);
                                } elseif (substr($firstN[$i], strlen($firstN[$i]) - 1, 1) == ')') {
                                    $fSymb = substr($firstN[$i], 0, 1);
                                    $firstN[$i] = strtoupper($fSymb) . ')';
                                } else {
                                    $fSymb = substr($firstN[$i], 0, 1);
                                    $firstN[$i] = strtoupper($fSymb);
                                }
                            }
                            $ShortName .= $firstN[$i];
                        }
                    }
                    $ShortName .= ' ';
                } else {
                    $ShortName .= $firstN[0] . ' ';
                }
            }

            $ShortName .= $cuurentN;

            if ($lastN != '') {
                $ShortName .= ' ';
                $lastN = explode(' ', $lastN);
                foreach ($lastN as $i => $element) {
                    if (strlen($lastN[$i]) > 1) {
                        $fSymb = substr($lastN[$i], 0, 1);
                        if ($fSymb != '"') {
                            if ($fSymb == '(') {
                                $fSymb = substr($lastN[$i], 1, 1);
                                $lastN[$i] = '(' . strtoupper($fSymb);
                            } elseif (substr($lastN[$i], strlen($lastN[$i]) - 1, 1) == ')') {
                                $fSymb = substr($lastN[$i], 0, 1);
                                $lastN[$i] = strtoupper($fSymb) . ')';
                            } else {
                                $fSymb = substr($lastN[$i], 0, 1);
                                $lastN[$i] = strtoupper($fSymb);
                            }
                        }
                        $ShortName .= $lastN[$i];
                    }
                }
            }
        }

        return $ShortName;
    }
}
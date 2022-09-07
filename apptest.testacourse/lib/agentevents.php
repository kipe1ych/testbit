<?
namespace Apptest\Testacourse;

use Apptest\Testacourse\Coursetable;
use Bitrix\Main\Type\DateTime;

class Agentevents {
    public static function eventHandler() {
        $arrVal = array('USD');
        $date = date('d/m/Y');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $out = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($out, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        $objDateTime = new DateTime();
        foreach($array['Valute'] as $valute) {
            if(in_array($valute['CharCode'], $arrVal)) {
                Coursetable::add(
                    array(
                        "CODE"=>$valute['CharCode'],
                        "DATE"=>$objDateTime,
                        "COURSE"=>$valute['Value']
                    )
                );
            }
        }

        return "Apptest\Testacourse\Agentevents::eventHandler();";
    }
}
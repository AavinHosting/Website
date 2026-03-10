<?php

require_once( dirname( __FILE__ ) . '/inc/config.inc.php' );
require_once ( dirname( __FILE__ ) . '/inc/class_order_bll.php' );
//require_once 'classes/class_order_bll.php';
$oder = new Order();
if (!function_exists("discardWeenEnd")) {

    function discardWeenEnd($start, $end) {
        // echo $start.'<br/>'; echo 'dfdf'.$end;
        $start = strtotime($start);
        $end = strtotime($end);
        $day = (24 * 60 * 60);
        $k = 0;




        for ($i = $start; $i <= $end; $i+=86400) {
            // echo date('l', $i).'<br/>';
            if (in_array(date('l', $i), array("Saturday", "Sunday"))) {
                $k++;
            }
        }
        // die;
        // echo $k; die;
        return $k;
    }

}

function excluedeHoliday($date) {
    $skippDateArr = array(
        '2014-12-25',
        '2014-12-26',
        '2015-01-01',
        '2015-01-26',
        '2015-03-06',
        '2015-04-03',
        '2015-10-02',
        '2015-10-22',
        '2015-11-11',
        '2015-12-25',
    );

    if (in_array($date, $skippDateArr)) {
        //echo 'yes '.$date;//die;
        $date = date("Y-m-d", strtotime('+1 days', strtotime($date)));
        return excluedeHoliday($date);
    } else {
        //echo 'yes '.$date; die;
        return $date;
    }
}

if (isset($_POST["action"]) && strlen($_POST["action"])) {
    $action = $_POST["action"];

    switch ($action) {
        case "loadService":
            $pageID = $_POST["pageID"];
            $mode = $_POST["mode"];
            $dataAr = $toolTipAr = $returnAr = array();
           
            $oderAr = array("3" => array("Layout" => "1", "Advanced" => "2", "WordPress" => "4", "Javascript" => "3"), "1" => array("Layout" => "1", "Advanced" => "2", "Javascript" => "3"), "2" => array("Email" => "1"), "4" => array("Layout" => "1", "Advanced" => "2", "Drupal" => "4", "Javascript" => "3"), "5" => array("Layout" => "1", "Advanced" => "2", "Joomla" => "4", "Javascript" => "3"), "6" => array("Layout" => "1", "Advanced" => "2", "Magento" => "4", "Javascript" => "3"), "7" => array("Layout" => "1", "Advanced" => "2", "Javascript" => "3"), "8" => array("Layout Option" => "1", "Advanced Options" => "2", "Javascript Options" => "3", "OpenCart Options" => "4"), "9" => array("Layout Option" => "1", "Advanced Options" => "2", "Javascript Options" => "3"), "10" => array("Layout Option" => "1", "Advanced Options" => "2", "Javascript Options" => "3", "Prestashop Options" => "4"), "11" => array("Layout Option" => "1", "Advanced Options" => "2", "Javascript Options" => "3", "Shopify Options" => "4"));
            $data = $oder->getServicevalue($pageID, $mode);
                      //echo"<pre>"; print_r($data);die;
            $tabOptionsAr = array("Layout" => "layoutoption", "Advanced" => "advanceoption", "Javascript" => "javascriptoption", "WordPress" => "srviceoption", "Magento Options" => "magentoOptions", "Drupal Options" => "drupaloptions", "Joomla Options" => "joomlaoptions", "Concrete Options" => "concreteoptions", "Bootstrap Options" => "bootstrapoptions", "OpenCart Options" => "opencartoptions", "Prestashop Options" => "prestashopoptions", "Shopify Options" => "shopifyoptions");
            foreach ((array) $data as $value) {
                $returnAr[$value["OptionName"]] = html_entity_decode(strip_tags($value["tooltip"]));
                if (in_array($value["OptionTypeName"], array_keys($dataAr))) {
                    if (in_array($value["OptionName"], array_keys($dataAr[$value["OptionTypeName"]]))) {
                        @$dataAr[$value["OptionTypeName"]][$value["OptionName"]][$value["ValueName"]] = $value["ValuePrice"];
                       // @$dataAr[$value["OptionTypeName"]][$value["OptionName"]]["AddPerPagePrice"] = $value["AddPerPagePrice"];
                    } else {
                        if (is_null($value["ValueName"]) || strlen($value["ValueName"]) == 0) {
                            $dataAr[$value["OptionTypeName"]][$value["OptionName"]] = $value["ValuePrice"];
                          //  $dataAr[$value["OptionTypeName"]][$value["OptionName"]]["AddPerPagePrice"] = $value["AddPerPagePrice"];
                        } else {
                            $dataAr[$value["OptionTypeName"]][$value["OptionName"]][$value["ValueName"]] = $value["ValuePrice"];
                        }
                    }
                } else {
                    if (is_null($value["ValueName"]) || strlen($value["ValueName"]) == 0) {
                        $dataAr[$value["OptionTypeName"]][$value["OptionName"]] = $value["ValuePrice"];
                    } else {
                        $dataAr[$value["OptionTypeName"]][$value["OptionName"]][$value["ValueName"]] = $value["ValuePrice"];
                    }

                    if (in_array($value["OptionTypeName"], array_keys($tabOptionsAr))) {
                        $dataAr[$value["OptionTypeName"]]["id"] = $tabOptionsAr["{$value["OptionTypeName"]}"];
                    }
                    if (in_array($value["OptionTypeName"], array_keys($oderAr[$pageID]))) {
                        $dataAr[$value["OptionTypeName"]]["order"] = $oderAr[$pageID][$value["OptionTypeName"]];
                    }
                }
            }
            
           
            
            $returnAr["status"] = "success";
            $returnAr["data"] = $dataAr;
            echo json_encode($returnAr);
            break;

        case 'loadServicePrice':
            $pageID = $_POST["pageID"];
            $mode = $_POST["mode"];
            $totalPages = (isset($_POST["page"]) && strlen($_POST["page"])) ? $_POST["page"] : 1;
            $price = $oder->getServiceBasicvalue($pageID, $mode);
            $priceAr = array();
            foreach ((array) $price as $key => $value) {
                foreach ((array) $value as $k => $v) {
                    $priceAr[$k] = $v;
                }
            }
            
            $priceAr["totalPrice"] = ($totalPages * $price[0]["AddPerPagePrice"]) + $price[0]["BasePrice"];
            echo json_encode(array("status" => "success", "price" => $priceAr));die;
            break;

        case 'loadBothServicePrice':
            $pageID = $_POST["pageID"];
           // $totalPages = (isset($_POST["page"]) && strlen($_POST["page"])) ? $_POST["page"] : 1;
            $price = $oder->getBothServicevalue($pageID);
           
            foreach ($price as $key => $val) {
                $baseprice[$val['ServiceTypeName']]['BasePrice'] = $val['BasePrice'];
                $baseprice[$val['ServiceTypeName']]['AddPerPagePrice'] = $val['AddPerPagePrice'];
            } 
//             echo"<pre>";
//            print_r($baseprice);
//            die;
            echo json_encode(array("status" => "success", "price" => $baseprice));
            break;

        case 'loadServiceEstDate':
            $estDate = (isset($_POST["estDate"]) && $_POST["estDate"] != "undefined") ? $_POST["estDate"] : 0;
            $newDateEx = (isset($_POST["newDateEx"]) && $_POST["newDateEx"] != "undefined") ? $_POST["newDateEx"] : 0;
            $date = date("Y-m-d");
            $dateN = date("Y-m-d", strtotime("+$estDate days"));
            $dateNEx = date("Y-m-d", strtotime("+$newDateEx days"));
            $end = strtotime($dateN);

            $day = (24 * 60 * 60);

            //echo $date.'<br/>'; 
            // echo $dateN.'<br/>';

            $actD = discardWeenEnd($date, $dateN);
            $actDB = discardWeenEnd($date, $dateNEx);

            //echo $actD.'<br/> fgf ';
            // echo $actDB.'<br/>'; die;

            if ($actD > 0) {
                $estDate = $estDate + $actD;
            }
            if ($actDB > 0) {
                $newDateEx = $newDateEx + $actDB;
            }

            $newEstDate1 = date("Y-m-d", strtotime("+$estDate days"));
            $newEstDate2 = date("Y-m-d", strtotime("+$newDateEx days"));

            if (in_array(date("l", strtotime($newEstDate1)), array("Saturday", "Sunday"))) {
                if (date("l", strtotime($newEstDate1)) == "Saturday")
                    $dateAdd1 = 2;
                else
                    $dateAdd1 = 1;
                $estDate = $estDate + $dateAdd1;
                $newEstDateN1 = date("Y-m-d", strtotime("+$estDate days"));
            }else {
                $newEstDateN1 = $newEstDate1;
            }
            if (in_array(date("l", strtotime($newEstDate2)), array("Saturday", "Sunday"))) {
                if (date("l", strtotime($newEstDate2)) == "Saturday")
                    $dateAdd2 = 2;
                else
                    $dateAdd2 = 1;
                $newDateEx = $newDateEx + $dateAdd2;
                $newEstDateN2 = date("Y-m-d", strtotime("+$newDateEx days"));
            }else {
                $newEstDateN2 = $newEstDate2;
            }

            $excludedNormalDateTimeStamp = excluedeHoliday(date("Y-m-d", strtotime($newEstDateN1)));
            // echo $excludedNormalDateTimeStamp.'dfdfd '.$newEstDateN1.' ghgh '.$excludedNormalDateTimeStamp; die;
            $excludedExpeditedDateTimeStamp = excluedeHoliday(date("Y-m-d", strtotime($newEstDateN2)));
            echo json_encode(array("status" => "success", "dateN" => date("j F, Y", strtotime($excludedNormalDateTimeStamp)), "dateNEx" => date("j F, Y", strtotime($excludedExpeditedDateTimeStamp))));

            //echo json_encode(array("status"=>"success","dateN"=>date("j F, Y",strtotime($newEstDateN1)),"dateNEx"=>date("j F, Y",strtotime($newEstDateN2))));
            break;

        case 'coupanCode':
            $couponValue = $_POST["couponValue"];
            $verifyCoupon = $oder->getVerifyCoupon($couponValue);
            echo json_encode($verifyCoupon);
            break;
    }
}
?>

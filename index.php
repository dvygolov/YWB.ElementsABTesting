<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/abtest.php';
require_once __DIR__.'/keitaro.php';

$abtest = new ABTest();
$dom = $abtest->get_random_test_dom();
echo $dom->saveHTML();
if (isset($rawClick)){
    $cId = $rawClick->getCampaignId();
    $subid = $rawClick->getSubId();
    if (isset($cId) && isset($subid)){
        $keitaro=new KeitaroHelper($cId, $subid, $abtest->_testName);
        $keitaro->update_click_params();
    }
}
?>

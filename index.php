<?php
require __DIR__.'/vendor/autoload.php';

$abtest = new ABTest();
$dom = $abtest->get_random_test_dom();
$cId = $rawClick->getCampaignId();
$subid = $rawClick->getSubId();
if (isset($cId) && isset($subid)){
    $keitaro=new KeitaroHelper($cId, $subid, $abtest->_testName);
    $keitaro->update_click_params();
}
echo $dom->saveHTML();
?>

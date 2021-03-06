<?php
use IvoPetkov\HTML5DOMDocument;
class ABTest
{
    public $_testName;

    public function get_random_test_dom($printDebug=false){
        $tFile = 'test.html';
        if (!is_file($tFile))
            die("Main HTML file not found!");

        $tSettingsFile = 'test.json';
        if (!is_file($tSettingsFile))
            die("Test settings file not found!");

        $html=file_get_contents($tFile);
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($html,67108864);

        $json = file_get_contents($tSettingsFile);
        $ts = json_decode($json, true);
        if (is_null($ts))
            die("You have errors in test.json file!");
        $testName = $ts['name'];
        $tests = $ts['tests'];
        $testsCount = count($tests);
        for ($i = 0; $i < $testsCount; $i++){
            $curTest = $tests[$i];

            $variable = $dom->querySelector($curTest['selector']);
            if (!isset($variable)) continue; //if there is no suitable element - continue

            $variationsCount = count($curTest['variations']);
            $j = rand(0,$variationsCount);
            if ($j == $variationsCount) continue;//don't use variation

            $curVariation = $curTest['variations'][$j];
            $testName.=" [{$curVariation['name']}]";

            if ($curTest['type'] === 'text')
                $variable->textContent = $curVariation['value'];
            else if ($curTest['type'] === 'attribute')
                $variable->setAttribute($curTest['attribute'], $curVariation['value']);
        }
        $this->_testName = $testName;

        $dom = $this->insert_yandex($ts['yaid'], $testName, $dom);

        if ($printDebug){
            $body = $dom->querySelector('body');
            $testNameNode = new DOMText($testName);
            $body->insertBefore($testNameNode, $body->firstChild);
        }
        return $dom;
    }

    private function insert_yandex($yaid, $yaname, HTML5DOMDocument $dom){
        $yaScript = $dom->createElement('script');
        $script = file_get_contents('yandex.js');
        $script = str_replace('{YAID}', $yaid, $script);
        $script = str_replace('{YANAME}', $yaname, $script);
        $yaScript->textContent = $script;
        $body = $dom->querySelector('body');
        $body->insertBefore($yaScript, $body->firstChild);
        return $dom;
    }
}
?>
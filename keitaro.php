<?php
class KeitaroHelper
{
    const TrackerUrl = "";
    const TrackerApiKey = "";
    const ParamName = "extra_param_9";

    private $_campaignAlias;
    private $_subid;
    private $_testName;

    public function __construct($campaignId, $subid, $testName)
    {
        $this->_campaignAlias = $this->get_campaign_alias($campaignId);
        $this->_subid = $subid;
        $this->_testName = $testName;
    }

    public function update_click_params()
    {
        $params=array();
        $params['_update_tokens']=1;
        $params['sub_id']=$this->_subid;
        $params[self::ParamName] = $this->_testName;

        $qs=http_build_query($params);
        $url=self::TrackerUrl.$this->_campaignAlias."?{$qs}";
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res=curl_exec($ch);
        $result=json_decode($res,true);
        curl_close($ch);
        return $result;
    }

    private function get_campaign_alias(string $campaignId):string
    {
        $json=$this->adminapi_request("campaigns/{$campaignId}");
        return $json['alias'];
    }

    private function adminapi_request(string $address,array $params=null)
    {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, self::TrackerUrl."admin_api/v1/{$address}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: '.self::TrackerApiKey));
        if (isset($params)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        $res=curl_exec($ch);
        $result=json_decode($res,true);
        curl_close($ch);
        return $result;
    }
}
?>
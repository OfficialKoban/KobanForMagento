<?php
class Koban_Magentoforkoban_Helper_KobanTrack extends Mage_Core_Helper_Data
{
	public function CookieVar()
	{
		$cook = $_COOKIE["kbntrk"];
		Mage::log('Cookie : '.$cook, null, 'koban.log');
		$arr = explode("|", $cook);
		$res = new stdClass();
		$res->cid = $arr[0];
		if (count($arr) > 4)
			$res->utm_campaign = $arr[4];
		if (count($arr) > 6)
			$res->utm_medium = $arr[6];
		if (count($arr) > 5)
			$res->utm_source = $arr[5];
		if (count($arr) > 8)
			$res->utm_term = $arr[8];
		if (count($arr) > 7)
			$res->utm_content = $arr[7];
		return $res;
	}
	
	public function PostTrack($lp, $postvar)
	{
		$url = Mage::getStoreConfig('tracking/api/urltracking',Mage::app()->getStore()).'/Form/sbm';
		$zid = Mage::getStoreConfig('tracking/api/apizone',Mage::app()->getStore());
		$fid = "561954a10dc3610dc0e854a1";
		
		$cook = $this->CookieVar();
		$kbnc = $cook->cid;
		$utmc = $cook->utm_campaign;
		$utmm = $cook->utm_medium;
		$utms = $cook->utm_source;
		$utmt = $cook->utm_term;
		$utmct = $cook->utm_content;
		
		$url .= "?id=".$fid."&cid=".$kbnc."&zid=".$zid."&_lp=".$lp."&utm_campaign=".$utmc."&utm_source=".$utms."&utm_medium=".$utmm."&utm_content=".$utmct."&utm_term=".$utmt;
		Mage::log('Tracking Submit : '.$url, null, 'koban.log');
		Mage::log("Vars : " + http_build_query($postvar));
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($postvar));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-ncApi: '.Mage::getStoreConfig('tracking/api/api',Mage::app()->getStore()),
			'X-ncUser: '.Mage::getStoreConfig('tracking/api/apiuser',Mage::app()->getStore())
		));
		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
				
		Mage::log('Result Code: '.$info['http_code'], null, 'koban.log');
		Mage::log('Result : '.$server_output, null, 'koban.log');
		$els = json_decode($server_output, true);
		$res = array();
		foreach ($els as $k=>$v){
			$lp = (object)$v;
			array_push($res, $lp);
		}
		return $res;
	}
}
?>
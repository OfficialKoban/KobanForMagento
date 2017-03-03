<?php
class Koban_Magentoforkoban_Helper_KobanAPI extends Mage_Core_Helper_Data
{
	public function Get($path)
	{
		$url = Mage::getStoreConfig('tracking/api/kobanserver',Mage::app()->getStore()).'/'.$path;
		Mage::log('API Get '.$url, null, 'koban.log');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
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
	
	public function Post($path, $postvar)
	{
		$url = Mage::getStoreConfig('tracking/api/kobanserver',Mage::app()->getStore()).'/'.$path;
		Mage::log('API Post '.$url, null, 'koban.log');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($postvar));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-ncApi: '.Mage::getStoreConfig('tracking/api/api',Mage::app()->getStore()),
			'X-ncUser: '.Mage::getStoreConfig('tracking/api/apiuser',Mage::app()->getStore()),
			'Accept: application/json'
		));
		Mage::log('st', null, 'koban.log');
		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		Mage::log('end', null, 'koban.log');
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
	public function PostJSON($path, $obj)
	{
		$url = Mage::getStoreConfig('tracking/api/kobanserver',Mage::app()->getStore()).'/'.$path;
		Mage::log('API JSON Post '.$url, null, 'koban.log');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, trim($url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		
		$data_string = json_encode($obj);
		Mage::log($data_string, null, 'koban.log');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-ncApi: '.Mage::getStoreConfig('tracking/api/api',Mage::app()->getStore()),
			'X-ncUser: '.Mage::getStoreConfig('tracking/api/apiuser',Mage::app()->getStore()),
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));
		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		$request_header_info = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		
		Mage::log('Result Code: '.$info['http_code'], null, 'koban.log');
		if ($info['http_code'] == 0){
			Mage::log(curl_error($ch), null, 'koban.log');
		}
		curl_close ($ch);
		
		Mage::log('Result : '.$server_output, null, 'koban.log');
		$els = json_decode($server_output);
		
		return $els;
	}
}
?>
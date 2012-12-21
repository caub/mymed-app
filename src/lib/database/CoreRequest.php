<?php

namespace lib\database;

/*
 * uses curl or any HTTP lib: Artax...
 */


class CoreRequest {
	
	protected $serviceUrl = null;
	
	function __construct($serviceUrl) {
		
		$this->serviceUrl = $serviceUrl;

		//lazy init
		$this->curl	= curl_init();
		if($this->curl === false) {
			trigger_error('Unable to init CURL : ', E_USER_ERROR);
		}
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($this->curl, CURLOPT_CAINFO, "/etc/ssl/certs/mymed.crt");
		
	}
	
	function sendGet($ressource, $data) {
		curl_setopt($this->curl, CURLOPT_URL, $this->serviceUrl.'/'.$ressource . '?'.http_build_query($data));
		
		$result = curl_exec($this->curl);
		if ($result === false) {
			throw new Exception("CURL Error : " . curl_error($this->curl));
		}
		return $result;
	}
	
	function sendPost($ressource, $data) {
		curl_setopt($this->curl, CURLOPT_URL, $this->serviceUrl .'/'. $ressource);
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
		$result = curl_exec($this->curl);
		if ($result === false) {
			throw new Exception("CURL Error : " . curl_error($this->curl));
		}
		return $result;
	}
	
}
?>

<?php

Class ApiMultibot extends RequestApi {
	
	protected $host = "http://api.multibot.in";
	protected $apikey;
	
	function __construct($apikey){
		$this->apikey = $apikey;
	}
	function RecaptchaV2($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "userrecaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Hcaptcha($sitekey, $pageurl ){
		$data = http_build_query([
			"method" => "hcaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Turnstile($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "turnstile",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Authkong($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "authkong",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Ocr($img){
		$data = http_build_query([
			"method" => "universal",
			"body" => $img
			]);
		return $this->getResult($data, "POST");
	}
	function AntiBot($source){
		$main = explode('"',explode('src="',explode('Bot links',$source)[1])[1])[0];
		if(!$main)return 0;
		$data["method"] = "antibot";
		$data["main"] = $main;
		$src = explode('rel=\"',$source);
		foreach($src as $x => $sour){
			if($x == 0)continue;
			$no = explode('\"',$sour)[0];
			$img = explode('\"',explode('src=\"',$sour)[1])[0];
			$data[$no] = $img;
		}
		$data = http_build_query($data);
		//print_r($data);
		$ua = "Content-type: application/x-www-form-urlencoded";
		$res = $this->getResult($data, "POST", $ua);
		return "+".str_replace(",","+",$res);
	}
}
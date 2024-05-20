<?php

//namespace App\Apikey;

//use App\Apikey\RequestApi;

Class ApiXevil extends RequestApi {
	
	protected $host = "https://sctg.xyz";
	protected $apikey;
	
	function __construct($apikey){
		$this->apikey = $apikey."|SOFTID1204538927";
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
		$data = "method=base64&body=".$img;
		//$ua = "Content-type: application/x-www-form-urlencoded";
		return $this->getResult($data, "POST");
	}
	function AntiBot($source){
		$main = explode('"',explode('data:image/png;base64,',explode('Bot links',$source)[1])[1])[0];
		if(!$main)return 0;
		$data = "key=".$this->apikey."&json=1&method=antibot&main=$main";
		$src = explode('rel=\"',$source);
		foreach($src as $x => $sour){
			if($x == 0)continue;
			$no = explode('\"',$sour)[0];
			$img = explode('\"',explode('data:image/png;base64,',$sour)[1])[0];
			$data .= "&$no=$img";
		}
		$res = $this->getResult($data, "POST");
		if($res)return "+".str_replace(",","+",$res);
		return 0;
	}
	function Teaserfast($main, $small){
		$data = http_build_query([
			"method" => "teaserfast",
			"main_photo" => $main,
			"task" => $small
		]);
		$ua = "Content-type: application/x-www-form-urlencoded";
		return $this->getResult($data, "POST",$ua);
	}
}
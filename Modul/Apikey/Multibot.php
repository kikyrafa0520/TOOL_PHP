<?php
Class RequestApi{
	function in_api($data, $method = "POST"){
		$data =  "key=".$this->apikey."&json=1&".$data;
		if($method == "GET")return json_decode(file_get_contents($this->host.'/in.php?'.$data),1);
		$opts = ['http' =>['method'  => 'POST','content' => $data]];
		return json_decode(file_get_contents($this->host.'/in.php', false, stream_context_create($opts)),1);
	}
	function res_api($api_id){
		$params = "?key=".$this->apikey."&action=get&id=".$api_id."&json=1";
		return json_decode(file_get_contents($this->host."/res.php".$params),1);
	}
	function getBalance(){
		$res =  json_decode(file_get_contents($this->host."/res.php?action=userinfo&key=".$this->apikey),1);
		return $res["balance"];
	}
	function wait($tmr){
		$sym = [' ─ ',' / ',' │ ',' \ ',];
		$timr = time()+$tmr;$a = 0;
		while(1){
			$res=$timr-time();
			if(!$res)break;
			print " bypass".$sym[$a % 4]." \r";
			usleep(100000);
			$a++;
		}
	}
	function getResult($data ,$method = 0){
		$get_in = $this->in_api($data ,$method);
		if(!$get_in["status"]){
			print $get_in["request"]."\n";
			return 0;
		}
		while(true){
			echo " bypass |   \r";
			$get_res = $this->res_api($get_in["request"]);
			if($get_res["request"] == "CAPCHA_NOT_READY"){
				echo " bypass ─ \r";
				$this->wait(10);
				continue;
			}
			if($get_res["status"])return $get_res["request"];
			return 0;
		}
	}
}
Class ApiMultibot extends RequestApi {
	public $apikey;
	
	function __construct($apikey){
		$this->host = "http://api.multibot.in";
		$this->apikey = $apikey;
	}
	function RecaptchaV2($sitekey, $pageurl){
		$data = "method=userrecaptcha&sitekey=$sitekey&pageurl=$pageurl";
		return $this->getResult($data);
	}
	function Hcaptcha($sitekey, $pageurl ){
		$data = "method=hcaptcha&sitekey=$sitekey&pageurl=$pageurl";
		return $this->getResult($data);
	}
	function Turnstile($sitekey, $pageurl){
		$data = "method=turnstile&sitekey=".$sitekey."&pageurl=".$pageurl;
		return $this->getResult($data "GET");
	}
	function Ocr($img){
		$data = "method=universal&body=".trim(str_replace('data:image/png;base64,','',$img));
		return $this->getResult($data);
	}
	function AntiBot($source){
		$main = explode('"',explode('src="',explode('Bot links',$source)[1])[1])[0];
		if(!$main)return 0;
		$data = "method=antibot&main=$main";
		$src = explode('rel=\"',$source);
		foreach($src as $x => $sour){
			if($x == 0)continue;
			$no = explode('\"',$sour)[0];
			$img = explode('\"',explode('src=\"',$sour)[1])[0];
			$data .= "&$no=$img";
		}
		$r = $this->getResult($data);
		if($r)return "+".str_replace(",","+",$r);
		return 0;
	}
}
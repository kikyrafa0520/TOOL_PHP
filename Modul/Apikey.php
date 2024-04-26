<?php

/**
 * TOOL FARM CRYPTO
 *
 * @server		: https://github.com/iewilmaestro/TOOL_PHP
 * @author		: iewil <purna.iera@gmail.com>
 *
 * @chanel
 *	- @youtube	: https://youtube.com/@iewil
 *	- @telegram	: https://t.me/MaksaJoin
 *
 *
 * @support
 *	- @PetapaGenit2
 *	- @Zhy_08
 *	- @itsaoda
 *	- @IPeop
 *	- @MetalFrogs
 *	- @all-member
 *
 * @apikey_bypass_captcha
 *	- multibot
 *	- xevil
 *
 * @apikey_bypass_shortlink
 *	- @bpsl06_bot
 *
 * please don't edit source script if u want this script work normaly
 *
 */
Class RequestApi{
	function in_api($content, $method, $header = 0){
		$param = "key=".$this->apikey."&json=1&".$content;
		if($method == "GET")return json_decode(file_get_contents($this->host.'/in.php?'.$param),1);
		$opts['http']['method'] = $method;
		if($header) $opts['http']['header'] = $header;
		$opts['http']['content'] = $param;
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
	function wait($xr,$tmr, $cap){
		if($xr < 50){$wr=h;}elseif($xr >= 50 && $xr < 80){$wr=k;}else{$wr=m;}
		$xwr = [$wr,p,$wr,p];
		$sym = [' ─ ',' / ',' │ ',' \ ',];
		$a = 0;
		for($i=$tmr*4;$i>0;$i--){
			print $xwr[$a % 4]." Bypass $cap $xr%".$sym[$a % 4]." \r";
			usleep(100000);
			if($xr < 99)$xr+=1;
			$a++;
		}
		return $xr;
	}
	function filter($method){
		if($method == "userrecaptcha")return "RecaptchaV2";
		if($method == "hcaptcha")return "Hcaptcha";
		if($method == "turnstile")return "Turnstile";
		if($method == "universal" || $method == "base64")return "Ocr";
		if($method == "antibot")return "Antibot";
		if($method == "authkong")return "Authkong";
		if($method == "teaserfast")return "Teaserfast";
	}
	function getResult($data ,$method, $header = 0){
		$cap = $this->filter(explode('&',explode("method=",$data)[1])[0]);
		$get_in = $this->in_api($data ,$method, $header);
		if(!$get_in["status"]){
			$msg = $get_in["request"];
			if($msg){
				print Error($msg.n);
			}else{
				print Error("in_api @".provider_api." something wrong\n");
			}
			return 0;
		}
		$a = 0;
		while(true){
			echo " Bypass $cap $a% |   \r";
			$get_res = $this->res_api($get_in["request"]);
			if($get_res["request"] == "CAPCHA_NOT_READY"){
				$ran = rand(5,10);
				$a+=$ran;
				if($a>99)$a=99;
				echo " Bypass $cap $a% ─ \r";
				$a = $this->wait($a,5, $cap);
				continue;
			}
			if($get_res["status"]){
				echo " Bypass $cap 100%";
				sleep(1);
				print "\r                              \r";
				print h."[".p."√".h."] Bypass $cap success";
				sleep(2);
				print "\r                              \r";
				return $get_res["request"];
			}
			print m."[".p."!".m."] Bypass $cap failed";
			sleep(2);
			print "\r                              \r";
			print Error($cap." @".provider_api." Error\n");
			return 0;
		}
	}
}
Class ApiMultibot extends RequestApi {
	public $apikey;
	
	function __construct($apikey){
		$this->host = "http://api.multibot.in";
		$this->provider = "multibot";
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
Class ApiXevil extends RequestApi {
	//public $apikey;
	
	function __construct($apikey){
		$this->host = "https://sctg.xyz";
		$this->apikey = $apikey."|SOFTID6192660395";
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
/*
$apikey = "SoulqKkCaWdD7iWx5WNq7y6QuMpuljHm";
$api = new ApiXevil($apikey);
$sitekey = "08f2c2d465d09d4dfd64eeb53f8b579f135b15abf170407747312a066f88b2a1";
$pageurl = "https://onlyfaucet.com/";
$authkong = $api->Authkong($sitekey, $pageurl);
print $authkong;
//a06ec146ea90f3befa42d8d830b790e9
*/
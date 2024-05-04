<?php

//namespace App\Apikey;

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
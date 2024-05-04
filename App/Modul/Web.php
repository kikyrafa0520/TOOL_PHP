<?php

Class Web {
	static function getCaptcha($r, $api, $patch) {
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		$hcap =  explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
		if($recap){
			$cap = $api->RecaptchaV2($recap, host.'ptc/view/'.$id);
			$data["captcha"] = "recaptchaV2";
			$data["g-recaptcha-response"] = $cap;
		}elseif($turnstile){
			$cap = $api->Turnstile($turnstile, host.'ptc/view/'.$id);
			$data["captcha"] = "turnstile";
			$data["cf-turnstile-response"] = $cap;
		}elseif($hcap){
			$cap = $api->Hcaptcha($hcap, host.'ptc/view/'.$id);
			$data["captcha"] = "hcaptcha";
			$data["g-recaptcha-response"] = $cap;
			$data["h-captcha-response"] = $cap;
		}else{
			return 0;
		}
		if(!$cap)return "continue";
		return $data;
	}
	static function getAntibot($r, $api) {
		if(explode('\"',explode('rel=\"',$r)[1])[0]){
			$atb = $api->AntiBot($r);
			if(!$atb)return "continue";
			$antibot = str_replace("+", " ", $atb);
			$data["antibotlinks"] = $antibot;
		}else{
			return;
		}
	}
	static function getInput($r) {
		preg_match_all('#<input type="(.*?)" name="(.*?)" value="(.*?)"#',$r,$input);
		for($i = 0; $i<count($input[0]);$i++){
			$clear = explode('"',$input[2][$i])[0];
			$data[$clear] = $input[3][$i];
		}
		return $data;
	}
	static function getData($r) {
		//timer
		$tmr1 = explode('-',explode('var wait = ',$r)[1])[0];
		$tmr2 = explode('-',explode('let wait = ',$r)[1])[0];
		$tmr3 = explode(';',explode("var time =",$r)[1])[0];
		$tmr4 = explode(';',explode("var timer =",$r)[1])[0];
		if($tmr1) {
			$data['tmr'] = $tmr1;
		}
		elseif($tmr2) {
			$data['tmr'] = $tmr2;
		}
		elseif($tmr3) {
			$data['tmr'] = $tmr3;
		}
		elseif($tmr4) {
			$data['tmr'] = $tmr4;
		}
		else{
			$data['tmr'] = 0;
		}
		
		//limit
		preg_match('/(\d{1,})\/(\d{1,})/',$r,$limit);
		if($limit[2]){
			$data['sisa'] = $limit[1];
			$data['limit'] = $limit[2];
		}
		return $data;
	}
	static function getResponse($r) {
		//succes
		//gagal
		//banned
	}
}
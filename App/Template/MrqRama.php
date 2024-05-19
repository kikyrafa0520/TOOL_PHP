<?php
/*
	* FITUR
	* Dashboard -> getDashboard() [No Api]
	* Manual Claim -> getFaucet() [Apikey]
	* Auto Claim -> getAutoClaim() [No Api]
	* Surf Ads -> getAds() [Apikey]
	* Read article -> getArticle() [No Api]
	* Task
	* Shortlinks
	* Games -> getGame($game,$id)
	* Dice Game
	* Mining
	* Offerwall
	* Achievements -> Getachievements()
*/

function h(){
	$h[] = "Host: ".parse_url(host)['host'];
	$h[] = "cookie: ".simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}
function getDashboard(){
	$r = curl(host."dashboard",h())[1];
	$get = @Web::getData($r);
	$data['cloudflare'] = ($get['cloundflare'])? 1: 0;
	$data['user'] = explode('</div>',explode('<div class="font-medium">',$r)[1])[0];
	$data['balance'] = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[1])[0];
	$data['energy'] = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[3])[0];
	return $data;
}
function getAutoClaim(){
	Title("Auto Claim");
	while(true){
		$r = curl(host."auto",h())[1];
		$get = @Web::getData($r);
		if($get['cloundflare']) return 1;
		$cost = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[2])[0];
		if(getDashboard()["energy"] < $cost)break;
		$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
		$tmr = explode(",",explode("let timer = ",$r)[1])[0];
		if($tmr){tmr($tmr);}
		$data= "token=".$token;
		$r = curl(host."auto/verify",h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($ss);
			$r = getDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("You Need Energy\n");
	print line();
}
function getGame($game,$id){
	Title("Games");
	while(true){
		$r = curl(host."games/play/".$game,h())[1];
		$get = @Web::getData($r);
		if($get['cloundflare']) return 1;
		$score = explode(";",explode("var required_score = ",$r)[1])[0];
		$csrf = explode("';",explode("var csrf_hash = '",$r)[1])[0];
		$data = "score=".$score."&csrf=".$csrf;
		$arr = ["x-requested-with: XMLHttpRequest"];
		tmr(10);
		$r = json_decode(curl(host."games/verify?id=".$id,array_merge($arr,h()),$data)[1],1);
		if($r["status"] == "success"){
			print Sukses(explode(",",$r["message"])[0]);
			$r = getDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
		if($r["status"] == "error"){
			Cetak("Game",$game);
			print Error($r["message"].n);
			print line();
			break;
		}
	}
}
function getArticle(){
	Title("Read article");
	while(true){
		$r = curl(host."articles",h())[1];
		$get = @Web::getData($r);
		if($get['cloundflare']) return 1;
		$id = explode("'",explode('article/view/',$r)[1])[0];;
		if(!$id)break;
		
		$r = curl(host.'article/view/'.$id,h())[1];
		$csrf = explode('"',explode('_token_name" value="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$slug = explode('"',explode('name="slug" value="',$r)[1])[0];
		
		$data = "csrf_token_name=$csrf&token=$token&slug=$slug";
		$r = curl(host.'articles/antibot',h(),$data)[0];
		$loc = trim(explode(n, explode('q=',explode('location:', $r)[1])[1])[0]);
		$arr = ['referer: https://www.google.com/'];
		$r = curl(urldecode($loc),array_merge($arr,h()))[1];
		$tmr = explode(';',explode('let timer = ',$r)[1])[0];
		if($tmr)tmr($tmr);
		$csrf = explode('"',explode('_token_name" value="',$r)[1])[0];
		$final = explode('"',explode('<form action="',$r)[1])[0];
		$data = "csrf_token_name=$csrf";
		$r = curl($final,h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($ss);
			$r = getDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("Article has finished\n");
	print line();
}
function Getachievements(){
	Title("Achievements");
	$r = curl(host."achievements",h())[1];
	$get = @Web::getData($r);
	if($get['cloundflare']) return 1;
	$list = explode('<div class="text-1xl font-medium leading-8">Daily Achievement</div>',$r);
	foreach($list as $a => $aciv){
		if($a == 0)continue;
		$url = explode('"',explode('<form action="',$aciv)[1])[0];
		$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" value="',$aciv)[1])[0];
		$uncomplet = explode('Uncompleted',$aciv)[1];
		$task = explode('</div>',explode('<div class="text-1xl font-medium leading-8 mt-6">Task: ',$aciv)[1])[0];
		if($uncomplet)continue;
		$data = "csrf_token_name=".$csrf;
		$r = curl($url,h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($task);
			print Sukses($ss);
			$r = getDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
}
function getAds(){
	Title("Surf Ads");
	global $api;
	while(true){
		$r = curl(host.'ads',h())[1];
		$get = @Web::getData($r);
		if($get['cloundflare']) return 1;
		$id = explode("'",explode('ads/view/',$r)[1])[0];
		if(!$id)break;
		$r = curl(host.'ads/view/'.$id,h())[1];
		$tmr = explode(';',explode('var timer = ',$r)[1])[0];
		$csrf = explode('"',explode('_token_name" value="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recaptcha = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		
		$data = "csrf_token_name=$csrf&token=$token";
		$arr = ["x-requested-with: XMLHttpRequest"];
		$r = json_decode(curl(host.'ads/antibot',array_merge($arr,h()),$data)[1],1);
		if($r['status'] == 'success'){
			tmr($tmr);
			$token = $r['token'];
			if($turnstile){
				$cap = $api->Turnstile($turnstile, host.'ads/view/'.$id);
				$data = "captcha=turnstile&cf-turnstile-response=$cap&";
			}elseif($recaptcha){
				$cap = $api->RecaptchaV2($recaptcha, host.'ads/view/'.$id);
				$data = "captcha=recaptchav2&g-recaptcha-response=$cap&";
			}else{
				print Error("Sitekey Error\n"); continue;
			}
			$data .= "csrf_token_name=$csrf&token=$token";
			$r = curl(host.'ads/verify/'.$id,h(),$data)[1];
			$ss = explode("`",explode("html: `",$r)[1])[0];
			if($ss){
				print Sukses($ss);
				$r = getDashboard();
				Cetak("Balance",$r["balance"]);
				Cetak("Energy",$r["energy"]);
				print line();
			}
		}
	}
	print Error("Ads has finished\n");
	print line();
}
function getFaucet(){
	Title("Manual Claim");
	global $api;
	while(true){
		$r = curl(host.'claim',h())[1];
		$get = @Web::getData($r);
		if($get['cloundflare']) return 1;
		$_sisa = explode('<',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[4])[0];
		$sisa = explode('/',$_sisa)[0];
		if($sisa < 1)break;
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];//299 - 1;
		if($tmr){
			Tmr($tmr);continue;
		}
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recaptcha = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		
		$data = Parsing($r);
		if($turnstile){
			$cap = $api->Turnstile($turnstile, host.'ads/view/'.$id);
			$data['captcha'] = 'turnstile';
			$data['cf-turnstile-response'] = $cap;
		}elseif($recaptcha){
			$cap = $api->RecaptchaV2($recaptcha, host.'ads/view/'.$id);
			$data['captcha'] = 'recaptchav2';
			$data['g-recaptcha-response'] = $cap;
		}else{
			print Error("Sitekey Error\n"); continue;
		}
		
		$r = curl(host.'claim/verify',h(),http_build_query($data))[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			Cetak("Claim",$_sisa);
			print Sukses($ss);
			$r = getDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("Faucet has finished\n");
	print line();
}
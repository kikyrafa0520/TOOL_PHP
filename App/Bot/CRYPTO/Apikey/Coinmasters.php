<?php
const
register_link = "https://coinmasters.online/?r=7484",
host = "https://coinmasters.online/",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/c/iewil";

function h($xml=0){
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	$h[] = "cookie: ".Simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
if(!ua())print "\n".line();

if(!$cek_api_input){
	$apikey = MenuApi();
	if(provider_api == "Multibot"){
		$api = New ApiMultibot($apikey);
	}else{
		$api = New ApiXevil($apikey);
	}
	$cek_api_input = 1;
}

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);
function Firewall(){
	global $api;
	while(1){
		$r = curl(host."firewall",h())[1];
		$csrf = explode('"',explode('name="csrf_token_name" value="',$r)[1])[0];
		$captcha = explode('"',explode('name="captchaType" value="',$r)[1])[0];
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if($turnstile){
			$cap = $api->Turnstile($turnstile, host."firewall");
			$data["cf-turnstile-response"] = $cap;
		}else
		if($recap){
			$cap = $api->RecaptchaV2($recap, host."firewall");
			$data["g-recaptcha-response"] = $cap;
		}else{
			continue;
		}
		if(!$cap)continue;
		$data["captchaType"] = $captcha;
		$data["csrf_token_name"] = $csrf;
		$r = curl(host."firewall/verify",h(),http_build_query($data))[1];
		if(preg_match('/Invalid Captcha/',$r))continue;
		Cetak("Firewall","Bypassed");
		return 0;
	}
}
function  GetDashboard(){
	dashbord:
	$r = Curl(host."dashboard",h())[1];
	if(preg_match('/Firewall/',$r)){
		Firewall();goto dashbord;
	}
	$user = explode('</',explode('<p class="mb-0 ms-1 mdb-text">',$r)[1])[0];
	$bal = explode('</',explode('<h5 class="mb-0 ms-1 lg-text">',$r)[1])[0];
	$_bal = explode('</',explode('<span class="primary xs-text" style="font-size:13px;">~',$r)[1])[0];
	$token = explode('</',explode('<h5 class="mb-0 ms-1 lg-text">',$r)[2])[0];
	$_token = explode('</',explode('<span class="primary xs-text" style="font-size:13px;">~',$r)[2])[0];
	return ["user"=>$user,"balance"=>$bal." [$_bal]","token"=>$token." [$_token]"];
}
function GetFaucet($patch){
	global $api;
	Title("faucet");
	while(true){
		$r = curl(host.$patch, h())[1];
		$sl = explode('</button>',explode('<button class="btn btn-primary btn-lg " disabled><i class="far fa-check-circle"></i>',$r)[1])[0];//You need to do 1 shortlinks to unlock
		if($sl){
			print Error($sl.n);
			print line();
			return 0;
		}
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare\n");
			hapus("Cookie");
			sleep(3);
			print line();
			return 1;
		}
		if($cek['fw']){
			Firewall();
		}
		//$sisa = explode('/',explode('<p class="lh-1 mb-1 font-weight-bold">',$r)[3])[0];
		//if(!$sisa)break;
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];
		if($tmr > 0){tmr($tmr);continue;}
		
		$csrf = explode('"',explode('_token_name" id="token" value="',$r)[1])[0];
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		$hcap =  explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
		if($recap){
			$cap = $api->RecaptchaV2($recap, host.$patch);
			$datacap = "&captcha=recaptchav2&g-recaptcha-response=".$cap;
		}elseif($turnstile){
			$cap = $api->Turnstile($turnstile, host.$patch);
			$datacap = "&captcha=turnstile&cf-turnstile-response=".$cap;
		}elseif($hcap){
			$cap = $api->Hcaptcha($hcap, host.$patch);
			$datacap = "&captcha=hcaptcha&g-recaptcha-response=".$cap."&h-captcha-response=".$cap;
		}else{
			print Error("Sitekey Error\n"); continue;
		}
		if(!$cap)continue;
		if(explode('\"',explode('rel=\"',$r)[1])[0]){
			$atb = $api->AntiBot($r);
			if(!$atb)continue;
			$data = "antibotlinks=".$atb."&csrf_token_name=".$csrf.$datacap;
		}else{
			$data = "csrf_token_name=".$csrf.$datacap;
		}
		$r = curl(host.$patch."/claim", h(), $data)[1];
		$ss = explode(".'", explode("Swal.fire('Info', '", $r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			$r = GetDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Token",$r["token"]);
			//Cetak("Sisa",$sisa-1);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}else{
			echo Error("Error response\n");
			//print $r;exit;
			$r = GetDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Token",$r["token"]);
			Cetak("Bal_Api",$api->getBalance());
			sleep(2);
			print line();
		}
	}
	print Error("Daily claim limit\n");
}
function GetPtc(){
	global $api;
	Title("ptc");
	while(true){
		$r = curl(host.'ptc',h())[1];
		$id = explode("'",explode("/ptc/view/",$r)[1])[0];
		if(!$id){
			break;
		}
		
		$r = curl(host.'ptc/view/'.$id,h())[1];
		
		$ptc = explode("'",explode("var url = '",$r)[1])[0];
		$ptc = parse_url($ptc)['host'];
		
		if($idptc == 0){
			Cetak("Visit",$ptc);
		}
		
		$csrf = explode('"',explode('<input type="hidden" name="csrf_token_name" value="',$r)[1])[0];
		$tmr = explode(';',explode('var timer = ',$r)[1])[0];
		if($tmr){tmr($tmr);}
		
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		$hcap =  explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
		if($recap){
			$cap = $api->RecaptchaV2($recap, host.'ptc/view/'.$id);
			$datacap = "captcha=recaptchav2&g-recaptcha-response=".$cap."&";
		}elseif($turnstile){
			$cap = $api->Turnstile($turnstile, host.'ptc/view/'.$id);
			$datacap = "captcha=turnstile&cf-turnstile-response=".$cap."&";
		}elseif($hcap){
			$cap = $api->Hcaptcha($hcap, host.'ptc/view/'.$id);
			$datacap = "captcha=hcaptcha&g-recaptcha-response=".$cap."&h-captcha-response=".$cap."&";
		}else{
			print Error("Sitekey Error\n"); continue;
		}
		
		if(!$cap)continue;
		$data = $datacap.'csrf_token_name='.$csrf;
		$r = curl(host.'ptc/verify/'.$id,h(),$data)[1];
		$ss = explode("'", explode("Swal.fire('Info', '", $r)[1])[0];
		print "\r             \r";
		if($ss) {
			Cetak("Sukses",$ss);
			$r = GetDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Token",$r["token"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
			$idptc = 0;
		}else{
			$idptc = 1;
			print Error("Invalid Captcha\n");
			print line();
		}
	}
	print Error("PTC Finish\n");
	print line();
}
function shortlink(){
	$shortlinks = new Shortlinks(ApiShortlink());
	while(true){
		$r = curl(host."links",h())[1];
		if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
			print Error("Cloudflare\n");
			hapus("Cookie");
			return 1;
		}
		if(preg_match('/Firewall/',$r)){
			Firewall();continue;
		}
		$list = explode('<div class="col-12 col-md-4">',$r);
		foreach($list as $a => $short){
			if($a == 0)continue;
			$go = explode('"',explode('<a href="',$short)[1])[0];
			$short_name = explode('</h5>',explode('<h5 class="mb-0  lg4-text">',$short)[1])[0];//Shortsme
			$limit = explode('/',explode('alt="">',$short)[1])[0];
			$cek = $shortlinks->Check($short_name);
			if ($cek['status']) {
				for($i = 1; $i <= $limit; $i ++ ){
					Cetak($short_name,$i);
					$r = curl($go,h())[1];
					
					$shortlink = explode('"',explode('location.href = "',$r)[1])[0];
					$bypas = $shortlinks->Bypass($cek['shortlink_name'], $shortlink);
					$pass = $bypas['url'];
					if($pass){
						tmr($bypas['timer']);
						$r = curl($pass,h())[1];
						if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
							print Error("Cloudflare\n");
							hapus("Cookie");
							return 1;
						}
						$ss = explode("'", explode("Swal.fire('Info', '", $r)[1])[0];
						if($ss){
							Cetak("Sukses",$ss);
							$r = GetDashboard();
							Cetak("Balance",$r["balance"]);
							Cetak("Token",$r["token"]);
							Cetak("SL_Api",$bypas['balance']);
							print line();
						}
					}
				}
			}
		}
		break;
	}
}
$offer = new Offerwall(host.'offerwall/', $apikey);
$r = GetDashboard();
if(!$r["user"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}

Cetak("Username",$r["user"]);
Cetak("Balance",$r["balance"]);
Cetak("Token",$r["token"]);
Cetak("Bal_Api",$api->getBalance());
print line();
menu:
Menu(1,"Faucet");
Menu(2,"Ptc");
Menu(3,"Offerwall [Excentiv]");
Menu(4,"Shortlinks");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
	if(GetFaucet("bonusclaim"))goto cookie;
	goto menu;
}elseif($pil == 2){
	GetPtc();
	goto menu;
}elseif($pil == 3){
	Title("Offerwall [Excentiv]");
	$r = $offer->Excentiv();
	if(!$r['status']){
		print Error($r['message'].n);
		print line();
	}
	goto menu;
}elseif($pil == 4){
	if(shortlink())goto cookie;
	goto menu;
}else{
	print Error("Bad Number Selected\n");
	print line();
	goto menu;
}


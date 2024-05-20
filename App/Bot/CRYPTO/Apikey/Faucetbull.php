<?php
const
host = "https://faucetbull.com/",
register_link = "https://faucetbull.com/?r=720",
typeCaptcha = "Recaptchav2",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	$h[] = "Host: ".parse_url(host)['host'];
	if($data)$h[] = "Content-Length: ".strlen($data);;
	$h[] = "User-Agent: ".ua();
	$h[] = "Cookie: ".simpan("Cookie");
	return $h;
}
function  GetDashboard(){
	dashbord:
	$r = Curl(host."dashboard",h())[1];
	if(preg_match('/Firewall/',$r)){
		Firewall();goto dashbord;
	}
	$user = explode('<',explode('key="t-henry">',$r)[1])[0];
	$bal = explode('</h4>',explode('<h4 class="mb-0">',$r)[1])[0];
	$en = explode('</h4>',explode('<h4 class="mb-0">',$r)[2])[0];
	return ["user"=>$user,"balance"=>$bal,"energy"=>$en];
}
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
function GetPtc(){
	global $api;
	Title("ptc");
	while(true){
		$r = curl(host.'ptc',h())[1];
		$id = explode('"',explode("/ptc/view/",$r)[1])[0];
		if(!$id){
			break;
		}
		
		$r = curl(host.'ptc/view/'.$id,h())[1];
		$ptc = explode("'",explode("var url = '",$r)[1])[0];
		$ptc = parse_url($ptc)['host'];
		
		if($idptc == 0){
			Cetak("Visit",$ptc);
		}
		
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
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
		$ss = explode('has',explode("Swal.fire('Good job!', '",$r)[1])[0];
		print "\r             \r";
		if($ss) {
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
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
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];
		if($tmr){tmr($tmr);continue;}
		
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
		$r = curl(host.$patch."/verify", h(), $data)[1];
		$ss = explode("has",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
			//Cetak("Sisa",$sisa-1);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}else{
			echo Error("Error response\n");
			//print $r;exit;
			Cetak("Balance",GetDashboard()["balance"]);
			Cetak("Bal_Api",$api->getBalance());
			sleep(2);
			print line();
		}
	}
	print Error("Daily claim limit\n");
}
function GetAutoFaucet(){
	Title("autofaucet");
	while(true){
		$r=curl(host."auto",h())[1];
		if(preg_match("/Firewall/",$r)){
			Firewall();
		}
		if(preg_match("/You don't have enough energy/",$r)){
			echo Error("You don't have enough energy".n);
			print line();break;
		}
		$tmr=explode(';',explode('var timeleft = ',$r)[1])[0];
		$token=explode('"',explode('name="token" value="',$r)[1])[0];
		
		if($tmr){tmr($tmr);}
		
		$data = "token=".$token;
		$r = curl(host."auto/verify",h(),$data)[1];
		$ss = explode('has',explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
			Cetak("Energy",GetDashboard()["energy"]);
			print line();
		}
	}
}
function Achievements(){
	Title("Achievements");
	$r = curl(host."achievements",h())[1];
	$list = explode('<div class="alert text-center alert-info"><i class="fas fa-exclamation-circle"></i>',$r)[1];
	$list = explode('<div class="media-body">',$list);
	foreach($list as $a => $aciv){
		if($a == 0)continue;
		$url = explode('"',explode('<form action="',$aciv)[1])[0];
		$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" value="',$aciv)[1])[0];
		$uncomplet = explode('"',explode('aria-valuenow="',$aciv)[1])[0];
		$task = explode('</b>',explode('</i> ',$aciv)[1])[0];
		if($uncomplet < 100){
			$status = explode('</b>',explode('aria-valuemax="100"> <b>',$aciv)[1])[0];//2987/ 5000 
			print Error($task." ".$status.n);
			print line();
			continue;
		}
		$data = "csrf_token_name=".$csrf;
		$r = curl($url,h(),$data)[1];
		$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Task",$task);
			Cetak("Sukses",$ss);
			$r = GetDashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
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
$offer = new Offerwall(host.'offerwall/', $apikey);

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

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
Cetak("Energy",$r["energy"]);
Cetak("Bal_Api",$api->getBalance());
print line();
menu:
Menu(1,"Faucet");
Menu(2,"Faucet auto");
Menu(3,"Ptc");
Menu(4,"Challenges");
Menu(5,"Offerwall [Excentiv]");
Menu(6,"Offerwall [Offers4crypto]");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
	if(GetFaucet("faucet"))goto cookie;
	goto menu;
}elseif($pil == 2){
	GetAutoFaucet();
	goto menu;
}elseif($pil == 3){
	GetPtc();
	goto menu;
}elseif($pil == 4){
	Achievements();
	goto menu;
}elseif($pil == 5){
	Title("Offerwall [Excentiv]");
	$r = curl(host.'offerlist',h())[1];
	$offerlist = explode('offerwall/',$r);
	foreach($offerlist as $a => $list){
		if($a == 0)continue;
		$offerwall_name = explode('"',$list)[0];
		if($offerwall_name == "excentiv"){
			$r = $offer->Excentiv();
			if(!$r['status']){
				print Error($r['message'].n);
				print line();
			}
			break;
		}
	}
	goto menu;
}elseif($pil == 6){
	Title("Offerwall [Offers4crypto]");
	$r = curl(host.'offerlist',h())[1];
	$offerlist = explode('offerwall/',$r);
	foreach($offerlist as $a => $list){
		if($a == 0)continue;
		$offerwall_name = explode('"',$list)[0];
		if($offerwall_name == "offers4crypto"){
			$r = $offer->Offers4crypto();
			if(!$r['status']){
				print Error($r['message'].n);
				print line();
			}
			break;
		}
	}
	goto menu;
}else{
	print Error("Bad Number Selected\n");
	print line();
	goto menu;
}

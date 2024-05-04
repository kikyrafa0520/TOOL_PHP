<?php
const
register_link = "https://polyearn.xyz/?r=1354",
host = "https://polyearn.xyz/",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/c/iewil";

function h(){
	$h = [
	"user-agent: ".ua(),
	"cookie: ".simpan("Cookie")];
	return $h;
}
function GetDashboard(){
	$r = curl(host.'dashboard',h())[1];
	$data['balance'] = explode('</p>',explode('<p>',explode('<div class="top-balance">',$r)[1])[1])[0];
	return $data;
}
function Getfaucet($patch){
	global $api;
	while(true){
		$r = curl(host.$patch,h())[1];
		if (preg_match('/Locked/', $r)) {
			print Error("Faucet locked\n");
			print line();
			$tmr = explode("'",explode("let wait = '",$r)[1])[0];
			tmr($tmr);continue;
		}
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			hapus("Cookie");
			print line();
			return 1;
		}
		if (preg_match('/faucet?linkrequired=true/', $r)) {
			print Error("Do shortlink to continue\n");
			print line();
			exit;
		}
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];
		
		if($tmr){
			tmr($tmr);continue;
		}
		$csrf = explode('"',explode('id="token" value="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){print Error("Sitekey Error\n"); continue;}
		$cap = $api->RecaptchaV2($sitekey, host.'faucet');
		if(!$cap)continue;
		
		$data = "csrf_token_name=".$csrf."&token=".$token."&captcha=recaptchav2&recaptchav3=&g-recaptcha-response=".$cap;
		$r = curl(host.$patch.'/verify',h(),$data)[1];
		$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}
	}
}
function Getptc(){
	global $api;
	while(true){
		$r = Curl(host.'ptc',h())[1];
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			hapus("Cookie");
			print line();
			return 1;
		}
		$ads = explode('</p>',explode('<p>',explode('<div class="balance">',$r)[1])[1])[0];
		$id = explode("'",explode('ptc/view/',$r)[1])[0];
		if(!$id)break;
		$r = Curl(host.'ptc/view/'.$id,h())[1];
		$tmr = explode(';',explode('var timer = ',$r)[1])[0];
		$csrf = explode('"',explode('name="csrf_token_name" value="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		if($tmr){tmr($tmr);}
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){print Error("Sitekey Error\n"); continue;}
		$cap = $api->RecaptchaV2($sitekey, host.'ptc/view/'.$id);
		if(!$cap)continue;
		$data = "captcha=recaptchav2&g-recaptcha-response=".$cap."&csrf_token_name=".$csrf."&token=".$token;
		$r = Curl(host.'ptc/verify/'.$id,h(),$data)[1];
		$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}
	}
}

function Getauto(){
	while(true){
		$r = curl(host.'auto',h())[1];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$tmr = explode(',',explode('let timer = ',$r)[1])[0];//3600,
		if($tmr)tmr($tmr);
		
		$data = "token=$token";
		$r = curl(host.'auto/verify',h(),$data)[1];
		$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["balance"]);
			print line();;
		}
	}
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
if(!Simpan("Cookie"))print "\n".line();
if(!ua())print "\n".line();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = GetDashboard();
if(!$r["balance"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}
Cetak("Balance",$r["balance"]);
print line();
menu:
Menu(1, "Ptc + Faucet [Apikey]");
Menu(2, "Autofaucet [No Apikey]");
$pil = readline(Isi("Number"));
print line();
if($pil == 2){
	Getauto();
	goto menu;
}else{
	if(!$cek_api_input){
		$apikey = MenuApi();
		if(provider_api == "Multibot"){
			$api = New ApiMultibot($apikey);
		}else{
			$api = New ApiXevil($apikey);
		}
		$cek_api_input = 1;
	}
	print line();
	if(Getptc())goto cookie;
	if(Getfaucet('faucet'))goto cookie;
	goto menu;
}


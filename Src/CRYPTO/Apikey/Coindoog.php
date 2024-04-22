<?php
const
host = "https://coindoog.com/",
register_link = "https://coindoog.com/?r=74172",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
}

function GetDashboard(){
	$r = curl(host.'dashboard',h())[1];
	$data['bal'] = explode('</p>',explode('<p>',explode('<div class="top-balance">',$r)[1])[1])[0];
	return $data;
}

function faucet(){
	global $api;
	while(true){
		$r = curl(host.'faucet',h())[1];
		$cek = GlobalCheck($r[1]);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			hapus("Cookie");
			print line();
			return 1;
		}
		$tmr = explode('-',explode('let wait = ',$r)[1])[0];
		if($tmr){
			tmr($tmr);continue;
		}
		$ci = explode('"',explode('id="token" value="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){print Error("Sitekey Error\n"); continue;}
		$cap = $api->RecaptchaV2($sitekey, host.'faucet');
		if(!$cap)continue;
		
		if(explode('\"',explode('rel=\"',$r)[1])[0]){
			$atb = $api->Antibot($r);
			if(!$atb)continue;
		}
		$data = "antibotlinks=".$atb."&ci_csrf_token=".$ci."&token=".$token."&captcha=recaptchav2&g-recaptcha-response=".$cap;
		$r = curl(host.'faucet/verify',h(),$data)[1];
		$ss = explode(' has',explode("text: '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["bal"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}else{
			print Error("Something wrong\n");
			print line();
		}
	}
}
function ptc(){
	global $api;
	while(true){
		$r = Curl(host.'ptc',h())[1];
		$cek = GlobalCheck($r[1]);
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
		$tmr = explode(';',explode('let timer = ',$r)[1])[0];
		$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
		if($tmr){tmr($tmr);}
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){print Error("Sitekey Error\n"); continue;}
		$cap = $api->RecaptchaV2($sitekey, host.'ptc/view/'.$id);
		if(!$cap)continue;
		$data = "captcha=recaptchav2&g-recaptcha-response=".$cap."&ci_csrf_token=&token=".$token;
		$r = Curl(host.'ptc/verify/'.$id,h(),$data)[1];
		$ss = explode('has',explode("text: '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["bal"]);
			Cetak("Bal_Api",$api->getBalance());
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

$apikey = MenuApi();
if(provider_api == "Multibot"){
	$api = New ApiMultibot($apikey);
}else{
	$api = New ApiXevil($apikey);
}

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = GetDashboard();
if(!$r["bal"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}
Cetak("Balance",$r["bal"]);
Cetak("Bal_Api",$api->getBalance());
print line();

if(ptc())goto cookie;
if(faucet())goto cookie;
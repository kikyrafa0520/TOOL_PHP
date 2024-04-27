<?php
const
host = "https://lovely-faucet.com/",
register_link = "https://lovely-faucet.com/?r=16322",
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

function faucet($patch){
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
		$r = curl(host.$patch.'/verify',h(),$data)[1];
		$ss = explode(' has',explode("text: '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",GetDashboard()["bal"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}
	}
}
function ptc(){
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

if(!$cek_api_input){
	$apikey = MenuApi();
	if(provider_api == "Multibot"){
		$api = New ApiMultibot($apikey);
	}else{
		$api = New ApiXevil($apikey);
	}
	$cek_api_input = 1;
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

menu:
Menu(1,"Faucet");
Menu(2,"Ptc");
Menu(3,"Unlimited Faucet");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
	if(faucet("faucet"))goto cookie;
	goto menu;
}elseif($pil == 2){
	if(ptc())goto cookie;
	goto menu;
}elseif($pil == 3){
	if(faucet("notimer"))goto cookie;
	goto menu;
}else{
	echo Error("Bad Number\n");
	print line();goto menu;
}
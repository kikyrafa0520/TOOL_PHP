<?php
const
host = "https://bitupdate.info/",
register_link = "https://bitupdate.info/?r=8227",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/@iewil";

function h(){
	$c=simpan("Cookie");
	return ["user-agent: ".ua(),"cookie: ".$c];
}
function  Get_Dashboard(){
	$url = host."dashboard";
	$r = Curl($url,h())[1];
	$user = explode('</h5>',explode('<h5 class="font-size-15 text-truncate">',$r)[1])[0];
	$bal = explode('</h4>',explode('<h4 class="mb-0">',$r)[1])[0];
	$en = explode('</h4>',explode('<h4 class="mb-0">',$r)[2])[0];
	return ["user"=>$user,"balance"=>$bal,"energy"=>$en];
}

function Claim($api, $patch){
	while(true):
	$r = curl(host.$patch, h())[1];
	if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
		print Error("Cloudflare\n");
		return "cf";
	}
	if(preg_match('/Firewall/',$r)){
		exit("Firewall\n");
	}
	$sisa = explode('/',explode('<p class="lh-1 mb-1 font-weight-bold">',$r)[3])[0];
	if(!$sisa)break;
	$tmr = explode('-',explode('var wait = ',$r)[1])[0];
	if($tmr){tmr($tmr);continue;}
			
	$data = @web::getInput($r);
	
	$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
	$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
	$hcap =  explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
	if($recap){
		$cap = $api->RecaptchaV2($recap, host.$patch);
		$data["recaptchav3"] = "";
		$data["captcha"] = "recaptchaV2";
		$data["g-recaptcha-response"] = $cap;
	}elseif($turnstile){
		$cap = $api->Turnstile($turnstile, host.$patch);
		$data["recaptchav3"] = "";
		$data["captcha"] = "turnstile";
		$data["cf-turnstile-response"] = $cap;
	}elseif($hcap){
		$cap = $api->Hcaptcha($hcap, host.$patch);
		$data["recaptchav3"] = "";
		$data["captcha"] = "hcaptcha";
		$data["g-recaptcha-response"] = $cap;
		$data["h-captcha-response"] = $cap;
	}else{
		print Error("Sitekey Error\n"); continue;
	}
	if(!$cap)continue;
	if(explode('\"',explode('rel=\"',$r)[1])[0]){
		$atb = $api->AntiBot($r);
		if(!$atb)continue;
		$antibot = str_replace("+", " ", $atb);
		$data["antibotlinks"] = $antibot;
	}else{
	}
	
	$r = curl(host.$patch."/verify", h(), http_build_query($data))[1];
	$ss = explode("has",explode("Swal.fire('Good job!', '",$r)[1])[0];
	if($ss){
		Cetak("Sukses",$ss);
		Cetak("Balance",Get_Dashboard()["balance"]);
		Cetak("Sisa",$sisa-1);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else{
		echo Error("Error\n");
		Cetak("Balance",Get_Dashboard()["balance"]);
		Cetak("Bal_Api",$api->getBalance());
		sleep(2);
		print line();
	}
	endwhile;
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

$r = Get_Dashboard();
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

$patch = "faucet";
$gas = Claim($api, $patch);
if($gas=="cf"){
	hapus("Cookie");
	goto cookie;
}
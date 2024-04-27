<?php
const
host = "https://bitupdate.info/",
register_link = "https://bitupdate.info/?r=8227",
typeCaptcha = "RecaptchaV2 | AntiBot",
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
function Get_Faucet($patch){
	$url = host.$patch;
	return Curl($url,h())[1];
}
function Post_Faucet($patch, $csrf,$atb,$cap){
	$url = host.$patch."/verify";
	$data = "antibotlinks=".$atb."&csrf_token_name=".$csrf."&captcha=recaptchav2&recaptchav3=&g-recaptcha-response=".$cap;
	return Curl($url,h(),$data)[1];
}
function Claim($api, $patch){
	while(true):
	$r = Get_Faucet($patch);
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
			
	$csrf = explode('"',explode('_token_name" id="token" value="',$r)[1])[0];
	$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
	if(!$sitekey){print Error("Sitekey Error\n"); continue;}
	$atb = $api->Antibot($r);
	if(!$atb){print Error("@".provider_api." Error\n"); continue;}
	$cap = $api->RecaptchaV2($sitekey, host.$patch);
	if(!$cap)continue;
	
	$r = Post_Faucet($patch, $csrf, $atb, $cap);
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
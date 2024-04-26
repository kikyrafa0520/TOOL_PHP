<?php
const
host = "https://faucet.payroute.online/",
register_link = "https://faucet.payroute.online/index?ref=fS6nEM8TyMwx5llJ6G9J",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
}

function GetDashboard(){
	$r = curl(host.'main.php',h())[1];
	$data['user'] = explode('</span>',explode('<span id="walletaddress">',$r)[1])[0];
	$data['wallet'] = explode('</span>',explode('<span id="walletaddress">',$r)[2])[0];
	return $data;
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
if(!ua())print "\n".line();

if(!$api){
	$apikey = MenuApi();
	if(provider_api == "Multibot"){
		$api = New ApiMultibot($apikey);
	}else{
		$api = New ApiXevil($apikey);
	}
}

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
Cetak("Email",$r["user"]);
Cetak("Coin",$r["wallet"]);
Cetak("Bal_Api",$api->getBalance());
print line();
exit(Error("Script on progres\n"));
date_default_timezone_set("UTC");
while(1){
	$r = curl(host.'main.php',h())[1];
	//$tmr = explode(';',explode('var claimTime = ',$r)[1])[0];
	//$cooldown = $tmr/1000-time();
	//print $cooldown.n;
	//exit;
	$sitekey = explode('>',explode('<div class="h-captcha" data-sitekey=',$r)[1])[0];
	if(!$sitekey){print Error("Sitekey Error\n"); continue;}
	$cap = $api->Hcaptcha($sitekey, host.'main.php');
	if(!$cap){print Error("@".provider_api." Error\n"); continue;}
	
	$data = "g-recaptcha-response=$cap&h-captcha-response=$cap&submitClaim=";
	$r = curl(host.'main.php',h(),$data)[0];
	print_r($r);exit;
	$direct = trim(explode("\n",explode("location:",$r)[1])[0]);
	$r = curl($direct,h())[1];
	print $r;exit;
}
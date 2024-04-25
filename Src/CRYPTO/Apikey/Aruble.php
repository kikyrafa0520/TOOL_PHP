<?php
const
register_link = "https://aruble.net/?r=01gLwYQYg1",
host = "https://aruble.net/",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/c/iewil";

function h($ref=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h = [
	"Host: ".$host[1],
	"origin: ".host,
	"content-type: application/x-www-form-urlencoded",
	"user-agent: ".ua(),
	"cookie: ".simpan("Cookie"),
	"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
	"accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"];
	if($ref){
		$h = array_merge($h,["referer: ".register_link]);
	}
	return $h;
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
if(!ua())print "\n".line();

$apikey = MenuApi();
if(provider_api == "Multibot"){
	$api = New ApiMultibot($apikey);
}else{
	$api = New ApiXevil($apikey);
}

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);
$r = curl(host."page/faucet/BTC/",h())[1];
if(preg_match('/One minute, please./',$r)){
	tmr(60);
	$r = curl(host."page/faucet/BTC/",h(),"click_ads=OK")[1];
}

$user = explode('</div>',explode('<div class="form-control ">Logged In As:',$r)[1])[0];
if(!$user){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}
Cetak("User",$user);
print line();
$list_coin = explode('<a href="https://aruble.net/page/faucet/',$r);
foreach($list_coin as $a => $coins){
	if($a == 0)continue;
	$coin = explode('/"',$coins)[0];
	Menu($a,$coin);
	$menu[$a] = $coin;
}
$pil = readline(Isi('Number'));
Ban(1);
menu:
$coin = $menu[$pil];
Cetak("Coin",$menu[$pil]);
Cetak("User",$user);
print line();

while(true){
	$r = curl(host."page/faucet/BTC/",h())[1];
	$user = explode('</div>',explode('<div class="form-control ">Logged In As:',$r)[1])[0];
	if(!$user){
		print Error("Session expired".n);
		hapus("Cookie");
		sleep(3);
		print line();
		goto cookie;
	}
	$r = curl(host."page/faucet/$coin",h())[1];
	if(preg_match('/You have to wait/',$r)){
		$tmr = explode(')',explode('claim_countdown("claim_again", "",',$r)[1])[0];// 236;
		tmr($tmr);
		continue;
	}
	if(preg_match('/One minute, please./',$r)){
		tmr(60);
		$r = curl(host."page/faucet/$coin",h(),"click_ads=OK")[1];
	}
	$hidden = explode('"',explode('<input type="hidden" id="',$r)[1])[0];
	$value = explode("'",explode("$hidden').val('",$r)[1])[0];
	$ghash = explode("'",explode("ghash').val('",$r)[1])[0];
	$hcaptcha = explode("'",explode("<div id='captcha' class='h-captcha' data-sitekey='",$r)[1])[0];
	if(!$hcaptcha){
		print Error("sitekey error!");
		sleep(6);
		print "\r                         \r";
		continue;
	}
	$cap = $api->Hcaptcha($hcaptcha, host."page/faucet/$coin");
	if(!$cap)continue;
	if(explode('\"',explode('rel=\"',$r)[1])[0]){
		$atb = $api->AntiBot($r);
		if(!$atb)continue;
	}else{
		print Error("atb error!");
		sleep(6);
		print "\r                         \r";
		continue;
	}
	$data = "$hidden=$value&ghash=$ghash&antibotlinks=$atb&g-recaptcha-response=$cap&h-captcha-response=$cap&claim=Verify";
	$r = curl(host."page/faucet/$coin",h(),$data)[1];
	$ss = trim(explode('</div>',explode('<div class="alert alert-success">',$r)[1])[0]);
	if(preg_match('/does not have sufficient/',$r)){
		print c.strtoupper($coin).": ".Error("The faucet does not have sufficient funds\n");
		print line();
		goto menu;
	}
	if(preg_match('/Your daily claim limit/',$r)){
		print Error("Your daily claim limit\n");
		print line();
		exit;
	}
	if($ss){
		print Sukses(trim(explode('of ',$ss)[1]));
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}
}
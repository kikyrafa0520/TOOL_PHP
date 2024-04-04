<?php
const
host = "https://adbch.top/",
register_link = "https://adbch.top/r/110267",
youtube = "https://youtu.be/7z9S_ZL4K3I";

function h(){
	$h[] = "Host: adbch.top";
	$h[] = "Upgrade-Insecure-Requests: 1";
	$h[] = "Connection: keep-alive";
	$h[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
	$h[] = "user-agent: ".ua();
	$h[] = "Referer: https://adbch.top/";
	$h[] = "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7";
	$h[] = "cookie: ".simpan("Cookie");
	return $h;
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();
/*
$email = simpan("Email_Fp");
$password = simpan("Password_Login");
*/
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = run(host."dashboard",h());
$userid=explode('</b></span>',explode('<span class="white-text">Ваш id: <b>',$r)[1])[0];
$bal=explode('</b>',explode('Balance<br><b>',$r)[1])[0];
if(!$bal){
	$bal = explode('</b></p>',explode('Баланс<br><b>',$r)[1])[0];
	if(!$bal){
		print Error("Session expired".n);
		hapus("Cookie");
		sleep(3);
		print line();
		goto cookie;
	}
}
Cetak("Userid",$userid);
Cetak("Balance",$bal);
print line();

while(true){
	$data = [];
	$r = run(host."surf/browse/",h());
	if(!preg_match("/Skip/",$r)){
	//if(!$price){
		print Error("Ads habis".n);
		print line();
		break;
	}
	preg_match_all('#<input type="hidden" name="(.*?)" value="(.*?)">#',$r,$x);
	foreach($x[1] as $a => $label){
		$data[$label] = $x[2][$a];
	}
	$data = http_build_query($data);
	
	$tmr = explode("'",explode("let duration = '",$r)[1])[0];
	if($tmr){tmr($tmr);}
	
	$r = run(host."surf/browse/",h(),$data);
	$ss = explode('BCH',explode('You earned ',$r)[1])[0];
	$bal = explode('</b>',explode('class="white-text bal">Баланс: <b>',$r)[1])[0];
	//if($ss){
		Cetak("Success",$price);
		$r = run(host."dashboard",h());
		$bal=explode('</b>',explode('Balance<br><b>',$r)[1])[0];
		Cetak("Balance",$bal);
		print line();
	//}
}
/*
if(explode(" BCH",$bal)[0] >= "0.0002"){
	Jam();
	$r = run(host."payeer/fpwithdraw/",h());
	$token = explode('">',explode('<input type="hidden" name="csrfmiddlewaretoken" value="',$r)[1])[0];
	
	$data = "csrfmiddlewaretoken=".$token."&bch_amount=0.0002&email=".urlencode($email)."&password=".$password;
	$r = run(host."payeer/fpwithdraw/",h(),$data);
	Cetak("Withdraw","0.0002 BCH");
	print line();
	$r = run(host."payeer/draw/payeersuccess/",h());
	$bal = explode('</b>',explode('Баланс: <b>',$r)[1])[0];
	Cetak("Balance",$bal);
	print line();
}
*/
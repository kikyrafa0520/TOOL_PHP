<?php
const
host = "freeusdt.click/",
register_link = "https://freeusdt.click/?r=328",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
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

$r = curl(host."dashboard",h())[1];
$user = explode('</b>',explode('<span id="greeting"></span> <b>',$r)[1])[0];
if(!$user){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}
$bal = explode(' USD</b>',explode('<b>Account Balance: ',$r)[1])[0];
$min = explode('"',explode('name="amount" min="',$r)[1])[0];

Cetak("User",$user);
Cetak("Balance",$bal);

$address = explode('">',explode('placeholder="Connect Your FaucetPay Email" value="',$r)[1])[0];
$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
if(!$address){
	$wallet = readline(Isi("Email fp"));
	$data = "csrf_token_name=".$csrf."&token=".$token."&wallet=".str_replace('@','%40',$wallet);
	$r = curl(host."dashboard/authorize",h(),$data)[1];
	$ss = explode("'",explode("html: '",$r)[1])[0];
	if($ss){
		print Sukses($ss);
		print line();
		sleep(2);
		goto cookie;
	}
}
Cetak("Wallet",$address);
Cetak("Bal_Api",$api->getBalance());
print line();

menu:
$wd = 0;
Menu(1,"Ads");
//if($bal >= $min){ $min = 1
if($bal >= 1){
	Menu(2, "Withdraw");
	$wd = 1;
}
$pil = readline(Isi("Nomor"));
print line();
if($pil == 1){
	goto ads;
}elseif($pil == 2 && $wd == 1){
	$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
	$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
	$coin = explode('"',explode('value="',explode('<input class="form-check-input" type="radio" name="currency"',$r)[1])[1])[0];
	$data = "csrf_token_name=".$csrf."&token=".$token."&amount=".substr($bal,0,5)."&currency=".$coin;
	$r = curl(host."withdraw",h(),$data)[1];
	$ss = explode("account!'",explode("html: '0.",$r)[1])[0];
	$wr = explode("'",explode("html: '",$r)[1])[0];
	if($ss){
		print Sukses("0.".$ss);
		print line();
	}else{
		print Error($wr.n);
		print line();
	}
	goto menu;
}else{
	echo Error("Bad Number\n");
	print line();
	goto menu;
}
ads:
while(true){
	$r = curl(host."ads",h())[1];
	$id = explode('"',explode('ads/view/',$r)[1])[0];
	if(!$id)break;
	$r = curl(host."ads/view/".$id,h())[1];
	$cek = GlobalCheck($r);
	if($cek['cf']){print Erro("Cloudflare Detect\n");hapus("Cookie");print line();goto cookie;}
	if($cek['fw']){print Erro("Firewall Detect\n");hapus("Cookie");print line();goto cookie;}
	$data = Parsing($r);
	$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
	if(!$sitekey){
		print Error("Sitekey Error\n");
		continue;
	}
	$cap = $api->RecaptchaV2($sitekey, host."ads/view/".$id);
	if(!$cap)continue;
	$data['captcha'] = 'recaptchav2';
	$data['g-recaptcha-response'] = $cap;
	
	$final = explode('"',explode('<form id="ptcform" action="',$r)[1])[0];
	$r = curl($final, h(), http_build_query($data))[1];
	$ss = explode("account!'",explode("html: '0.",$r)[1])[0];
	$wr = explode("'",explode("html: '",$r)[1])[0];
	if($ss){
		print Sukses("0.".$ss);
		print line();
	}else{
		print Error($wr.n);
		print line();
	}
}
print Error("Ads are finished\n");
print line();
goto menu;
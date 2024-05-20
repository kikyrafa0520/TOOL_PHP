<?php
const
host = "https://autofaucet.dutchycorp.space/",
register_link = "https://autofaucet.dutchycorp.space/?r=anjim127",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/@iewil";

function h($data=0,$au=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	$h[] = "User-Agent: ".ua();
	$h[] = "Cookie: ".simpan("Cookie");
	return $h;
}
function GetDashboard(){
	$r= curl(host,h())[1];
	$data["user"] = explode("';",explode("username = '",$r)[1])[0];
	$data["bal"] = explode('&nbsp;',explode('<p>Your DUTCHY: <br><b>',$r)[1])[0];
	return $data;
}
function GetClaim($api, $patch, $captcha, $sitekey){
	while(true){
		$r = curl(host.$patch,h())[1];
		if(preg_match('/Cloudflare/',$r)){
			print "\r               \r";
			print Error("Clodflare");
			sleep(5);
			print "\r               \r";
			break;
		}
		$tmr = explode(';',explode('var timeleft = ',$r)[1])[0];
		if($tmr){
			sleep(3);
			break;
		}
		$id_boost = explode('"',explode('id="claim_boosted" name="',$r)[1])[0];
		$val_boost = explode('"',explode('btn.value = "',$r)[1])[0];
		if($captcha == "Hcaptcha"){
			$cap = $api->Hcaptcha($sitekey, host.$patch);
			if(!$cap)continue;
			$data = "g-recaptcha-response=".$cap."&h-captcha-response=".$cap."&".$id_boost."=".$val_boost;
		}else{
			$cap = $api->RecaptchaV2($sitekey, host.$patch);
			if(!$cap)continue;
			$data = "g-recaptcha-response=".$cap."&".$id_boost."=".$val_boost;
		}
		
		$r = curl(host.$patch,h(),$data)[1];
		if($patch == 'coin_roll.php' || $patch == 'bonus_roll.php' ){
			preg_match('#<center><p>Lucky Number is:</p><br><span class="font-color" style="font-size: 4.2rem;">(.*?)</span></center><div class="card white-text center-align green darken-3 pulse"><p>(.*?)</p></div>#is',$r,$hasil);
			if($hasil[2]){
				Cetak("Number",$hasil[1]);
				print Sukses($hasil[2]);
				Cetak("Bal_Api",$api->getBalance());
				print line();
				break;
			}else{
				continue;
			}
		}
		if($patch == 'roll.php'){
			preg_match('#<center><p>Lucky Number is:</p><br><span class="font-color" style="font-size: 4.2rem;">(.*?)</span></center><div class="card blue darken-3 white-text center-align pulse">(.*?)<img style="margin-bottom: 2.5px;" height="21px" width="21px" src="https://autofaucet.dutchycorp.space/assets/images/crypto-icons/DUTCHY-2.png"> (.*?) </div>#is',$r,$hasil);
			if($hasil[3]){
				Cetak("Number",$hasil[1]);
				print Sukses($hasil[2].$hasil[3]);
				$r = GetDashboard();
				Cetak("Balance",$r["bal"]);
				Cetak("Bal_Api",$api->getBalance());
				print line();
				break;
			}
		}
	}
}
function GetPtc($api, $captcha, $sitekey){
	while(true){
		$r = curl("https://autofaucet.dutchycorp.space/ptc/wall.php", h())[1];
		$id = explode("')",explode("'/ptc/view.php?vid=",$r)[1])[0];
		if(!$id){
			print Error("Ptc Habis\n");
			print line();
			return 1;
		}
		$ptc = explode('</b></p>',explode('<p style="font-size: 1.3em;margin-block-start: 12px;"><b>',$r)[1])[0];
		Cetak("Visit",$ptc);
		
		$r = curl("https://autofaucet.dutchycorp.space/ptc/view.php?vid=".$id, h())[1];
		$hash = explode('"',explode('name="hash" value="',$r)[1])[0];
		
		$tmr = explode(";",explode("var timer = ",$r)[1])[0];
		if($tmr){
			tmr($tmr);
		}
		if($captcha == "Hcaptcha"){
			$cap = $api->Hcaptcha($sitekey, "https://autofaucet.dutchycorp.space/ptc/view.php?vid=".$id);
			if(!$cap)continue;
			$data = "hash=".$hash."&g-recaptcha-response=".$cap."&h-captcha-response=".$cap;
		}else{
			$cap = $api->RecaptchaV2($sitekey, "https://autofaucet.dutchycorp.space/ptc/view.php?vid=".$id);
			if(!$cap)continue;
			$data = "hash=".$hash."&g-recaptcha-response=".$cap;
		}
		
		$ua = [
		"Host: autofaucet.dutchycorp.space",
		"user-agent: ".ua(),
		"referer: https://autofaucet.dutchycorp.space/ptc/view.php?vid=".$id,
		"cookie: ".simpan("Cookie")
		];
		
		$r = curl("https://autofaucet.dutchycorp.space/ptc/set-ptc-settings.php?viewadurl=".$id, $ua, $data)[1];
		$ss = explode('added',explode('<i class="fas fa-check"></i>&nbsp;&nbsp; ',$r)[1])[0];
		if($ss){
				print Sukses($ss);
				$r = GetDashboard();
				Cetak("Balance",$r["bal"]);
				Cetak("Bal_Api",$api->getBalance());
				print line();
		}
	}
}
function GetTimer($patch){
	sleep(3);
	$r = curl(host.$patch,h())[1];
	$tmr = explode(';',explode('var timeleft = ',$r)[1])[0];
	if(!$tmr){$tmr = 5;}
	return $tmr;
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
if(!$r["user"]){
	print Error("Cookie expired".n);
	hapus("Cookie");
	print line();
	sleep(3);
	goto cookie;
}

Cetak("Username",$r["user"]);
Cetak("Balance",$r["bal"]);
$r = curl(host.'bonus_roll.php',h());
$check = trim(explode('date',explode('HTTP/2',$r[0])[1])[0]);
if($check >= 201){
	$bonus_roll = "FREE";
}else{
	$bonus_roll = "PLATINUM";
}
Cetak("User",$bonus_roll);
$r = curl(host."account.php",h())[1];
$hcaptcha = explode('>',explode('<div class="h-captcha" data-sitekey=',$r)[1])[0];
$recaptcha = explode('>',explode('<div class="g-recaptcha" data-sitekey=',$r)[1])[0];

if($recaptcha){
	$sitekey = str_replace(['"',"'"],'',$recaptcha);
	$captcha = "RecaptchaV2";
}else
if($hcaptcha){
	$sitekey = str_replace(['"',"'"],'',$hcaptcha);
	$captcha = "Hcaptcha";
}else{
	$captcha = "IconCaptcha";
	print Error("i cant bypas IconCaptcha\n");
	print Error("change you captcha on setting and update cookie");
	hapus("Cookie");
	print line();
	sleep(3);
	goto cookie;
}
Cetak("Captcha",$captcha);
print line();

menu:
Menu(1, "Earn Dutchy");
Menu(2, "Ptc Wall");
Menu(3, "Autofaucet");
$pil = readline(Isi("Number"));
print line();
if($pil==1){goto earn;
}elseif($pil==2){goto ptc;
}elseif($pil==3){goto auto;
}else{print Error("Bad Number\n");print line();goto menu;}

earn:
GetClaim($api, "roll.php", $captcha, $sitekey);
GetClaim($api, "coin_roll.php", $captcha, $sitekey);
if($bonus_roll == "PLATINUM"){
	GetClaim($api, "bonus_roll.php", $captcha, $sitekey);
}
sleep(5);
$tmr = [];
$tmr[0] = GetTimer("roll.php");
$tmr[1] = GetTimer("coin_roll.php");
if($bonus_roll == "PLATINUM"){
	$tmr[2] = GetTimer("bonus_roll.php");
}
Tmr(min($tmr));
goto earn;

ptc:
GetPtc($api, $captcha, $sitekey);
exit;

auto:
$cokie = Simpan("Cookie_Autofaucet");
ban(1);
$uas = array();
$uas[] = "user-agent: ".ua();
$uas[] = "cookie: ".$cokie;

$r = curl(host.'faucet.php',$uas)[1];
$timer=trim(explode(';',explode('var timer = ',$r)[1])[0]);
if(!$timer){
	print Error("Wrong Cookie".n);
	hapus("Cookie_Autofaucet");
	print line();
	sleep(3);
	goto auto;
}
$tmr = explode('</span>',explode('<span id="sec">',$r)[1])[0];
if($tmr){tmr($tmr);}
while(true){
	$r = curl(host.'faucet.php',$uas)[1];
	$token = trim(explode('</span>',explode('<span class="dutchy_balance" style="white-space: nowrap;">',$r)[1])[0]);
	Cetak("Balance", $token);
	$has = explode('z-depth-5 faa-horizontal">',$r);
	for($i=1;$i<count($has);$i++){
		$hasil = explode('<a',$has[$i])[0];
		$payment = explode('">',explode('<a class="font-color" target="_blank" href="',$has[$i])[1])[0];
		preg_match("@^(?:/)?([^.]+)@i", $payment,$string);
		$pay = str_replace('https://',"",$string[1]);
		print Sukses($hasil.$pay);
	}
	print line();
	tmr($timer);
}
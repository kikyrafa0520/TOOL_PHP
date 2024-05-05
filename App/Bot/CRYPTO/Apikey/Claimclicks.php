<?php
const
register_link = "https://claimclicks.com/eth/?r=iewilmaestro",
host = "https://claimclicks.com/",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/c/iewil",
r = "/?r=iewilmaestro";

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
		$h = array_merge($h,["referer: ".$ref]);
	}
	return $h;
}

Ban(1);
cookie:
hapus("cookie.txt");
Cetak("Register",register_link);
print line();
$email = simpan("Username_Faucetpay");
simpan("Cookie");
/*
if(explode('@',$email)[1]){
	hapus("Username_Faucetpay");
	print error("invalid username!\n");
	print line();
	goto cookie;
}
*/
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

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

while(true){
	$r = Curl(host,h())[1];
	$cek = GlobalCheck($r[1]);
	if($cek['cf']){
		print Error("Cloudflare Detect\n");
		hapus("Cookie");
		hapus("cookie.txt");
		print line();
		goto cookie;
	}
	$coinx = explode('class="btnbtc" href="/',$r);
	foreach($coinx as $a => $coin){
		if($a == 0)continue;
		$coint = explode('"',$coin)[0];
		if($res){
			if($res[$coint] > 2)continue;
		}
		$r = curl(host.$coint.r,h(),'',1)[1];
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			print Error("claim manual 1x , jika berhasil maka ambil cookie di halaman https://claimclicks.com\n");
			hapus("Cookie");
			hapus("cookie.txt");
			print line();
			goto cookie;
		}
		if(preg_match('/You have to wait/',$r)){
			$res = his([$coint=>1],$res);
			continue;
		}
		$user_id = explode('"',explode('Only allowed" name="',$r)[1])[0];
		$sitekey = explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){
			print Error("sitekey error!");
			sleep(6);
			print "\r                         \r";
			continue;
		}
		$cap = $api->Hcaptcha($sitekey, host.$coint);
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
		
		$data = "g-recaptcha-response=".$cap."&h-captcha-response=".$cap."&".$user_id."=".$email."&antibotlinks=".$atb;
		
		$r = curl(host.$coint.r,h(host.$coint.r),$data,1)[1];
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			print Error("claim manual 1x , jika berhasil maka ambil cookie di halaman https://claimclicks.com\n");
			hapus("Cookie");
			hapus("cookie.txt");
			print line();
			goto cookie;
		}
		$ss = explode('<',explode('<div class="alert alert-success">',$r)[1])[0];
		$wr = explode('</div>',explode('<div class="alert alert-danger">',$r)[1])[0];
		if(preg_match('/invalid address/',$r)){
			print error("invalid address\n");
			print line();
			hapus("Username_Faucetpay");
			goto cookie;
		}
		if(preg_match('/does not have sufficient/',$r)){
			print c.strtoupper($coint).": ".Error("The faucet does not have sufficient funds\n");
			$res = his([$coint=>3],$res);
			print line();
			continue;
		}
		if(preg_match('/Your daily claim limit/',$r)){
			print c.strtoupper($coint).": ".Error("Your daily claim limit\n");
			$res = his([$coint=>3],$res);
			print line();
			continue;
		}
		if($ss){
			print Sukses(trim($ss)." Faucetpay!");
			Cetak("Bal_Api",$api->getBalance());
			print line();
			$res = his([$coint=>1],$res);
		}elseif($wr){
			$wr = explode('</div>',explode('<div class="alert alert-danger">',$r)[1])[0];
			if($wr == "Please complete Shortlink first."){
				print c.strtoupper($coint).": ".Error("Please complete Shortlink first.\n");
				$res = his([$coint=>3],$res);
				continue;
			}
			print c.strtoupper($coint).": ".Error($wr.n);
			print line();
			$res = his([$coint=>1],$res);
		}else{
			continue;
			print_r($r);exit;
		}
	}
	if(min($res) > 2)break;
}
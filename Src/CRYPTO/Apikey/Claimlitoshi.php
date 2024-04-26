<?php
const
host = "https://claimlitoshi.top/",
register_link = "https://claimlitoshi.top/?r=2963",
typeCaptcha = "Authkong",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
}

function GetDashboard(){
	$r = curl(host.'dashboard',h())[1];
	$data['user'] = trim(str_replace([">","\r","\n"],'',strip_tags(explode('class="pt-3 text-lg font-medium text-slate-700 dark:text-navy-100"',$r)[1])));
	$data['bal'] = trim(str_replace(["\r","\n"],'',strip_tags(explode('</p>',explode('Balance</p>',$r)[1])[0])));
	$data['aft'] = trim(str_replace(["\r","\n"],'',strip_tags(explode('</p>',explode('AFT Balance</p>',$r)[1])[0])));
	return $data;
}

function Claimfct(){
	global $api;
	while(true){
		$r = curl(host.'faucet',h())[1];
		$cek = GlobalCheck($r[1]);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			hapus("Cookie");
			print line();
			return 'cf';
		}
		$res_limit = trim(str_replace(["\r","\n"],'',strip_tags(explode('</p>',explode('Claim Limit</p>',$r)[1])[0])));
		$handle_limit = explode('/',$res_limit);
		if($handle_limit[0] < 1)break;
		$limit = str_replace($handle_limit[0],$handle_limit[0]-1,$res_limit);
		
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];
		if($tmr){tmr($tmr);continue;}
		
		$data = Parsing($r);
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$authkong = explode('"',explode('<div class="authkong_captcha" data-sitekey="',$r)[1])[0];
		if($turnstile){
			$cap = $api->Turnstile($turnstile, host.'faucet');
			if(!$cap)continue;
			$data["captcha"] = "turnstile";
			$data["cf-turnstile-response"] = $cap;
		}elseif($authkong){
			$cap = $api->Authkong($authkong, host.'faucet');
			if(!$cap)continue;
			$data["captcha"] = "authkong";
			$data["captcha-response"] = $cap;
		}else{
			print Error("Sitekey Error\n");
			continue;
		}
		$r = curl(host.'faucet/verify',h(),http_build_query($data))[1];
		$ss = explode('</p>',explode('Good job!</h2><p class="mt-2">',$r)[1])[0];//13 Tokens has been added to your balance
		if($ss){
			print Sukses($ss);
			Cetak("Limit",$limit);
			$r = GetDashboard();
			Cetak("Balance",$r["bal"]);
			Cetak("Aft",$r["aft"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}
	}
}

function ClaimAft(){
	while(true){
		$r = curl('https://autofaucet.top/earn',h())[1];
		print_r($r);exit;
		print_r(Parsing($r));exit;
		//<div class="cf-turnstile" data-sitekey="0x4AAAAAAAFVKRJrs30bi0BA">
	}
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
if(!Simpan("Cookie"))print "\n".line();
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
Cetak("Username",$r["user"]);
Cetak("Balance",$r["bal"]);
Cetak("Aft",$r["aft"]);
Cetak("Bal_Api",$api->getBalance());
print line();
$x = Claimfct();
if($x == 'cf')goto cookie;
/*
menu:
Menu(1,"Claim Aft");
Menu(2,"Claim Faucet");
$pil = readline(Isi("Nomor"));
print line();
if($pil == 1){
	ClaimAft();
}else
if($pil == 2){
	$x = Claimfct();
	if($x == 'cf')goto cookie;
}
goto menu;
*/
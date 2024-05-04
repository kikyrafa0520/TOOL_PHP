<?php
const
host = "https://cashbux.work/",
register_link = "https://cashbux.work/?r=17799",
typeCaptcha = "Turnstile",
youtube = "https://youtube.com/@iewil";

function h(){
	$c=simpan("Cookie");
	return ["user-agent: ".ua(),"cookie: ".$c];
}
function  Get_Dashboard(){
	dashbord:
	$url = host."dashboard";
	$r = Curl($url,h())[1];
	if(preg_match('/Firewall/',$r)){
		Firewall();goto dashbord;
	}
	$user = explode('<p>',explode('<h5 class="font-size-15 text-truncate">',$r)[1])[0];
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
	$data = "antibotlinks=".$atb."&csrf_token_name=".$csrf."&captcha=turnstile&cf-turnstile-response=".$cap;
	return Curl($url,h(),$data)[1];
}
function Firewall(){
	global $api;
	while(1){
		$r = curl(host."firewall",h())[1];
		$csrf = explode('"',explode('name="csrf_token_name" value="',$r)[1])[0];
		$captcha = explode('"',explode('name="captchaType" value="',$r)[1])[0];
		$turnstile = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$recap = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if($turnstile){
			$cap = $api->Turnstile($turnstile, host."firewall");
			$data["cf-turnstile-response"] = $cap;
		}else
		if($recap){
			$cap = $api->RecaptchaV2($recap, host."firewall");
			$data["g-recaptcha-response"] = $cap;
		}else{
			continue;
		}
		if(!$cap)continue;
		$data["captchaType"] = $captcha;
		$data["csrf_token_name"] = $csrf;
		$r = curl(host."firewall/verify",h(),http_build_query($data))[1];
		if(preg_match('/Invalid Captcha/',$r))continue;
		Cetak("Firewall","Bypassed");
		return 0;
	}
}
function Claim($api, $patch){
	while(true):
	$r = Get_Faucet($patch);
	$sl = explode('</button>',explode('<button class="btn btn-primary btn-lg " disabled><i class="far fa-check-circle"></i>',$r)[1])[0];//You need to do 1 shortlinks to unlock
	if($sl){
		print Error($sl.n);
		return 0;
	}
	if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
		print Error("Cloudflare\n");
		return 1;
	}
	if(preg_match('/Firewall/',$r)){
		Firewall();continue;
	}
	$sisa = explode('/',explode('<p class="lh-1 mb-1 font-weight-bold">',$r)[3])[0];
	if(!$sisa)break;
	$tmr = explode('-',explode('var wait = ',$r)[1])[0];
	if($tmr){tmr($tmr);continue;}
			
	$csrf = explode('"',explode('_token_name" id="token" value="',$r)[1])[0];
	$sitekey = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
	if(!$sitekey){print Error("Sitekey Error\n"); continue;}
	$atb = $api->Antibot($r);
	if(!$atb)continue;
	$cap = $api->Turnstile($sitekey, host.$patch);
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
	print Error("Daily claim limit\n");
}
function auto(){
	while(true){
		$r=curl(host."auto",h())[1];
		if(preg_match("/Firewall/",$r)){
			exit(Error("Firewall\n"));
		}
		if(preg_match("/You don't have enough energy/",$r)){
			echo Error("You don't have enough energy".n);
			print line();break;
		}
		$tmr=explode(',',explode('let timer = ',$r)[1])[0];
		$token=explode('"',explode('name="token" value="',$r)[1])[0];
		
		if($tmr){tmr($tmr);}
		
		$data = "token=".$token;
		$r = curl(host."auto/verify",h(),$data)[1];
		$ss = explode('has',explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Sukses",$ss);
			Cetak("Balance",Get_Dashboard()["balance"]);
			Cetak("Energy",Get_Dashboard()["energy"]);
			print line();
		}
	}
}
function ptc($api){
	while(true){
		$r = curl(host.'ptc',h())[1];
		$id = explode("'",explode("/ptc/view/",$r)[1])[0];
		if(!$id){
			break;
		}
		$r = curl(host.'ptc/view/'.$id,h())[1];
		$ptc = explode("'",explode("var url = '",$r)[1])[0];
		$ptc = explode("/",$ptc)[2];
		
		if($idptc == 0){
			print Cetak("Visit",$ptc);
		}
		$sitekey = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		$token = explode('"',explode('name="token" value="',$r)[1])[0];
		$csrf = explode('"',explode('<input type="hidden" name="csrf_token_name" value="',$r)[1])[0];
		$tmr = explode(';',explode('var timer = ',$r)[1])[0];
		if($tmr){tmr($tmr);}
		$cap = $api->Turnstile($sitekey, host.'ptc/view/'.$id);
		if(!$cap)continue;
		$data = 'captcha=turnstile&cf-turnstile-response='.$cap.'&csrf_token_name='.$csrf.'&token='.$token;
		$r = curl(host.'ptc/verify/'.$id,h(),$data)[1];
		$ss = explode('has',explode("Swal.fire('Good job!', '",$r)[1])[0];
		print "\r             \r";
		if($ss) {
			Cetak("Sukses",$ss);
			Cetak("Balance",Get_Dashboard()["balance"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
			$idptc = 0;
		}else{
			$idptc = 1;
			print Error("Invalid Captcha\n");
		}
	}
	print Error("Ptc Habis\n");
	print line();
}
function aciv(){
	$r = curl(host."achievements",h())[1];
	$list = explode('</table>',explode('<table class="table table-centered table-nowrap mb-0">',$r)[1])[0];
	$list = explode('<tr>',$list);
	foreach($list as $a => $aciv){
		if($a <= 1)continue;
		$url = explode('"',explode('<form action="',$aciv)[1])[0];
		$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" value="',$aciv)[1])[0];
		$uncomplet = explode('"',explode('aria-valuenow="',$aciv)[1])[0];
		$task = explode('</td>',explode('<td>',$aciv)[1])[0];
		if($uncomplet < 100)continue;
		$data = "csrf_token_name=".$csrf;
		$r = curl($url,h(),$data)[1];
		$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
		if($ss){
			Cetak("Task",$task);
			Cetak("Sukses",$ss);
			$r = Get_Dashboard();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
}
function shortlink(){
	$shortlinks = new Shortlinks(ApiShortlink());
	while(true){
		$r = curl(host."links",h())[1];
		if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
			print Error("Cloudflare\n");
			hapus("Cookie");
			return 1;
		}
		if(preg_match('/Firewall/',$r)){
			Firewall();continue;
		}
		$list = explode('<div class="col-lg-3">',$r);
		foreach($list as $a => $short){
			if($a == 0)continue;
			$go = explode('"',explode('<a href="',$short)[1])[0];
			$short_name = explode('</h4>',explode('<h4 class="card-title mt-0">',$short)[1])[0];//Shortsme
			$limit = explode('/',explode('<span class="badge badge-info">',$short)[1])[0];
			
			$cek = $shortlinks->Check($short_name);
			if ($cek['status']) {
				for($i = 1; $i <= $limit; $i ++ ){
					Cetak($short_name,$i);
					$r = curl($go,h())[1];
					$shortlink = explode('"',explode('location.href = "',$r)[1])[0];
					$bypas = $shortlinks->Bypass($cek['shortlink_name'], $shortlink);
					$pass = $bypas['url'];
					if($pass){
						tmr($bypas['timer']);
						$r = curl($pass,h())[1];
						if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){
							print Error("Cloudflare\n");
							hapus("Cookie");
							return 1;
						}
						$ss = explode("'",explode("Swal.fire('Good job!', '",$r)[1])[0];
						if($ss){
							Cetak("Sukses",$ss);
							$r = Get_Dashboard();
							Cetak("Balance",$r["balance"]);
							Cetak("SL_Api",$bypas['balance']);
							print line();
						}
					}
				}
			}
		}
		break;
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
menu:
Menu(1, "Earn Coin");
Menu(2, "Shortlinks");
$pil = readline(Isi("Number"));
print line();
if($pil == 2){
	if(shortlink())goto cookie;
	goto menu;
}else{
	while(true){
		ptc($api);
		if(Claim($api, "faucet")){
			hapus("Cookie");
			goto cookie;
		}
		auto();
		aciv();
		tmr(600);
	}
}
<?php
const
host = "https://maticpick.io/",
register_link = "https://maticpick.io/?ref=anjim127",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	$h[] = "x-requested-with: XMLHttpRequest";
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
}

function GetDashboard(){
	$r = curl(host.'faucet.php',h())[1];
	$data['user'] = trim(explode('<',explode('16px;font-weight:bold">Welcome back, ',$r)[1])[0]);
	$data['bal'] = explode('<',explode('class="user_balance">',$r)[1])[0];
	return $data;
}

function HourlyFaucet($api){
	while(true){
		$r = curl(host.'faucet.php',h());
		$tmr = explode('|',explode('select_hourly_faucet|',$r[1])[1])[0];
		if(is_numeric($tmr)){
			return $tmr;
		}
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $r[0], $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		$cap = $api->Hcaptcha("2827ab4d-e726-41fd-a05f-a2b0575a3c7b", host.'faucet.php');
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data = 'action=claim_hourly_faucet&g-recaptcha-response=null&h-captcha-response='.$cap.'&captcha=&ft=&csrf_test_name='.$cookies['csrf_cookie_name'];
		
		$r = json_decode(curl(host.'process.php',h(),$data)[1],1);
		if($r["ret"]){
			Cetak("Type","Hourly Faucet");
			Cetak("Number",$r["num"]);
			print Sukses($r["mes"]);
			Cetak("Balance",GetDashboard()["bal"]);
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}
	}
}

function ClaimBonus(){
	while(true){
		$r = curl(host.'faucet.php',h());
		$bonus = explode('</span>',explode('<span id="free_spins">',$r[1])[1])[0];
		if(!$bonus)return 0;
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $r[0], $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		$data = "action=claim_bonus_faucet&csrf_test_name=".$cookies['csrf_cookie_name'];
		$r = json_decode(curl(host.'process.php',h(),$data)[1],1);
		if($r["ret"]){
			Cetak("Type","Bonus Faucet");
			Cetak("Number",$r["num"]);
			print Sukses($r["mes"]);
			Cetak("Balance",GetDashboard()["bal"]);
			print line();
		}
	}
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
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
Cetak("Bal_Api",$api->getBalance());
print line();

while(true){
	$bonus = ClaimBonus();
	$limit = HourlyFaucet($api);
	if($limit)Tmr($limit);
}
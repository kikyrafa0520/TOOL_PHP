<?php
const
host = "https://buxwall.com/",
register_link = "https://buxwall.com/ref/iewilmaestro",
typeCaptcha = "Turnstile",
youtube = "https://youtube.com/@iewil";

function h(){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	$h[] = "Cookie: ".simpan("Cookie");
	$h[] = "User-Agent: ".ua();
	return $h;
}

function GetDashboard(){
	$r = curl(host.'account',h())[1];
	$data['user'] = explode('"',explode(host.'ref/',$r)[1])[0];
	$data['bal'] = explode('</span>',explode('<span id="balance">',$r)[1])[0];
	return $data;
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
	$r = curl(host."faucet",h())[1];
	//$atb = explode('</script>',explode("<script>var ablinks=",$r)[1])[0];
	$tmr=explode(';',explode('let timeLeft = ',$r)[1])[0];
	if($tmr){tmr($tmr);}
	
	$csrf=explode('"',explode('<input type="hidden" name="csrfToken" value="',$r)[1])[0];
	if(explode('\"',explode('rel=\"',$r)[1])[0]){
		$atb = $api->AntiBot($r);
		if(!$atb)continue;
	}else{
		print Error("atb error!");
		sleep(6);
		print "\r                         \r";
		continue;
	}
	$sitekey = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
	if(!$sitekey){
		print Error("Sitekey Error\n");
		continue;
	}
	$cap = $api->Turnstile($sitekey, host.'faucet');
	if(!$cap)continue;
	
	$data = "csrfToken=".$csrf."&antibotlinks=".$atb."&cf-turnstile-response=".$cap;
	$r = curl(host."faucet",h(),$data)[1];
	$notif=explode("',",explode("type: '",$r)[5])[0];
	if($notif=="success"){
		print "\r                            \r";
		$ss = explode("'",explode("message: '",$r)[1])[0];
		Cetak("Success",$ss);
		$r = curl(host."account",h())[1];
		$bal= explode('</span>',explode('<span id="balance">',$r)[1])[0];
		Cetak("Balance",$bal);
		print line();
	}else{
		print "\r                            \r";
		$danger=explode("'",explode("message: '",$r)[1])[0];
		if($danger=="You reached the maximum daily claims, get back tomorrow for more earnings."){
			print Error($danger."\n");
			break;
		}
		print Error($danger);
		sleep(2);
		print "\r                                  \r";
	}
}
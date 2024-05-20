<?php
const
host = "https://free-litecoin.com/",
register_link = "https://free-litecoin.com/login?referer=1931549",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtu.be/S6wRxEjbCYQ";

function h($xml=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	//$h[] = "cookie: ".Simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}
function dash(){
	$r = curl(host.'profile',h(),'',1)[1];
	$bal = explode('</a>',explode('id="money">',$r)[1])[0];
	$user = explode('"',explode('<input type="email" class="form-control" value="',$r)[1])[0];
	return ["user"=>$user,"bal"=>$bal];
}
function login($api, $email, $pass){
	$cap = $api->RecaptchaV2("6Le64rAcAAAAABViuAh1IlT5Foo2qqo96kGoS29i",host);
	if(!$cap)return 0;
	$data = [
	"email" => $email,
	"heslo" => $pass,
	"g-recaptcha-response" => $cap
	];
	$r = curl(host."login",h(),http_build_query($data),1);
	curl(host."authentification.php",h(),'',1);
	return $r;
}
function gabut($str){
	for($i = 0;$i < 10; $i ++){
		print h."Number: ".p.rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		usleep(rand(100000,1000000));
		print "\r        \r";
	}
	print h."Number: ".p.$str;
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
$email = Simpan("Email");
$pass = Simpan("Password");
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

login:
if(!file_exists("Data/FreeLitecoin/cookie.txt")){
	if(!login($api, $email, $pass))goto login;
}

$r = dash();
if(!$r["bal"]){
	print Error("Session expired".n);
	hapus("cookie.txt");
	sleep(3);
	print line();
	goto cookie;
}

Cetak("Email",$r["user"]);
Cetak("Balance",$r["bal"]);
Cetak("Bal_Api",$api->getBalance());
print line();

while(true){
	$r = curl(host,h(),'',1)[1];
	$tmr = explode(';',explode("var time =",$r)[1])[0];
	if($tmr){
		tmr(floor($tmr/1000)+1);
		continue;
	}
	$img = explode('"',explode('<img src="data:image/png;base64,',$r)[1])[0];
	if(!$img){
		continue;
	}
	$cap = $api->Ocr($img);
	if(!$cap)continue;
	
	$data = "recaptcha=".$cap;
	$r = json_decode(curl(host.'php/rollnumber.php',h(1),$data,1)[1],1);
	if($r["state"]){
		gabut($r["roll"]);
		print m." | ".h."You Won: ".p.$r["value"].n;
		Cetak("Balance",$r["valuefinal"]);
		Cetak("Bal_Api",$api->getBalance());
		print line();
		tmr($r["secondtime"]/1000);
	}
}






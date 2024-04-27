<?php
const
host = "https://sleepandearn.online/",
register_link = "https://sleepandearn.online/index.php?ref=3460",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/@iewil";

function h(){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	$h[] = "User-Agent: ".ua();
	return $h;
}
function login($api, $user, $pass){
	ulang:
	$cap = $api->Hcaptcha('01e97a01-3deb-46a3-968c-fb4d128cab44', host."login");
	if(!$cap)goto ulang;
	$data = http_build_query([
	"email" => $user,
	"password" => $pass,
	"remember_me" => "yes",
	"g-recaptcha-response" => $cap,
	"h-captcha-response" => $cap,
	"login" => "login"
	]);
	return curl(host."login", h(), $data,1)[1];
}
function getBalance(){
	$r = curl(host.'account/summary', h(), '',1)[1];
	return Satoshi(explode("<span", explode('<h5 class="font-weight-bolder mb-0">', $r)[1])[0]);
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
$email = Simpan("Email");
$password = Simpan("Password");
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
if(preg_match('/Login/',curl(host.'account/summary', h(), '',1)[1])){
	hapus("cookie.txt");
	login($api, $email, $password);goto login;
}
Cetak("Balance",getBalance());
Cetak("Bal_Api",$api->getBalance());
print line();
$r = curl(host."account/money/claims", h(), 0, 1)[1];
preg_match_all('#<tr class="tcb">(.*?)</tr>#is',$r,$has);

foreach($has[1] as $a => $dt){
	$number = explode('</td>',explode('<td>',$dt)[1])[0];
	$reward = explode('</td>',explode('<td>',$dt)[2])[0];
	$rand1 = explode('-',$number)[0];
	$rand2 = explode('-',$number)[1];
	if(!$rand2)$rand2=$number;
	$cit[$a]["rand1"] = $rand1;
	$cit[$a]["rand2"] = $rand2;
	$cit[$a]["reward"] = $reward;
	Menu($a, $number." | ".$reward);
	$a++;
}
$cit[$a]["rand1"] = 0000;
$cit[$a]["rand2"] = 10000;
$cit[$a]["reward"] = "Normal";
Menu($a, "0000-10000 | Normal");
$x = readline(Isi("Nomor"));
print line();
if($x == '' || $x >= $a+1)exit(Error("Tolol\n"));
while(true){
	$bal1 = getBalance();
	if(!$bal1){
		hapus("cookie.txt");
		goto login;
	}
	$r = curl(host."account/money/claims", h(), 0, 1)[1];
	$tmr = explode('"',explode('id="roll_next" value="',$r)[1])[0];
	if($tmr){
		tmr($tmr);continue;
	}
	$sitekey = explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
	if(!$sitekey){
		print Error("Sitekey Error\n");
		continue;
	}
	$cap = $api->Hcaptcha($sitekey, host."account/money/claims");
	if(!$cap)continue;
	$num = rand($cit[$x]["rand1"],$cit[$x]["rand2"]);
	$data = "roll_num=".$num."&g-recaptcha-response=".$cap."&h-captcha-response=".$cap;
	$r = curl(host.'callbacks/faucetroll.php',h(),$data, 1)[1];
	if($r){
		Cetak("Number",$num);
		$bal2 = getBalance();
		if($cit[$x]["reward"] == "Normal"){
			Cetak("Reward",Satoshi($bal2-$bal1));
		}else{
			Cetak("Reward",$cit[$x]["reward"]);
		}
		Cetak("Balance",$bal2);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}
}

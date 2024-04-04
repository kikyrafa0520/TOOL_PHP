<?php
const
register_link = "https://coinmediahub.com?r=purna.iera@gmail.com",
host = "https://coinmediahub.com/",
youtube = "https://youtube.com/@iewil";

function h(){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	$h[] = "referer: ".register_link;
	$h[] = "user-agent: ".ua();
	return $h;
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
$w = simpan("Email_Faucetpay");
ua();

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
system("termux-open-url ".youtube);Ban(1);

Cetak("User",simpan("Email_Faucetpay"));
print line();

while(1){
	$r = Curl(register_link, h(),'',1)[1];
	$left = explode(' claims left today.', explode('<br> You have ', $r)[1])[0];
	$cd = explode(' minute', explode('<i class="fas fa-exclamation-triangle"></i> You have to wait ', $r)[1])[0];
	if (preg_match('/You have to wait/', $r)) {
		Tmr($cd*60+rand(5, 10)); continue;
	}
	if (preg_match('/(Daily claim limit|send limit)/', $r) || $left < 1) {
		exit(Error(" Daily limit reached\n"));
	}
	$tk = explode('"', explode('name="session-token" value="', $r)[1])[0];
	$cap = explode('"', explode('name="captcha" id="captcha" value="', $r)[1])[0];
	$data = "session-token=$tk&address=$w&antibotlinks=&captcha=$cap";
   
	$r = Curl(register_link, h(), $data,1)[1];
	$ss = explode('<', explode('<i class="fas fa-money-bill-wave"></i> ', $r)[1])[0];
	if (preg_match('/(claim limit|send limit)/', $r)) {
		exit(Error("Daily limit reached\n"));
	}elseif(preg_match('/Captcha was invaild/', $r)){
		print Error("Captcha was invaild\n");
		sleep(rand(3, 5));
	} elseif (preg_match('/sufficient funds/', $r)) {
		exit(Error("Sufficient funds"));
	} elseif ($ss) {
		print Sukses($ss);
	}
}
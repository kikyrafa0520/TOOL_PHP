<?php
const
register_link = "https://gwaher.com/",
host = "https://gwaher.com/",
youtube = "https://youtu.be/F7vu095xt9I";

function h(){
	$h = [
	"cookie: ".simpan("Cookie"),
	"user-agent: ".ua()
	];
	return $h;
}
Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
system("termux-open-url ".youtube);Ban(1);

$r = curl(host."dashboard",h())[1];
$bal = explode('"',explode('id="tokenBalance" value="',$r)[1])[0];//3000 Coins
$user = explode('</strong>',explode('<span style="color:#FDB750"><strong>',$r)[1])[0];
if(!$user){
	print Error("Cookie Expired!\n");
	hapus("Cookie");
	goto cookie;
}
Cetak("Username",$user);
Cetak("Balance",$bal." Coins");
print line();

while(true){
	$r = curl(host."ptc",h())[1];
	if(preg_match('/Cloudflare/',$r)){
		print Error("Cloudflare detect!\n");
		hapus("Cookie");
		goto cookie;
	}
	$id = explode('"',explode('id: "',$r)[1])[0];
	if(!$id){
		print Error("Ads Habis\n");
		print line();
		break;
	}
	$csrf = explode('"',explode('csrf_token_name: "',$r)[1])[0];
	$token = explode('"',explode('token: "',$r)[1])[0];
	$tmr = explode(',',explode('let timer = ',$r)[1])[0];
	if($tmr){tmr($tmr);}
	
	$data = "id=".$id."&csrf_token_name=".$csrf."&token=".$token;
	$r = curl(host."ptc/verify/".$id,h(),$data)[1];
	sleep(2);
	$r = curl(host."dashboard",h())[1];
	$balx = explode('"',explode('id="tokenBalance" value="',$r)[1])[0];//3000 Coins
	if($balx > $bal){
		Cetak("Sukses",($balx-$bal)." Coins");
		Cetak("Balance",$balx." Coins");
		print line();
		$bal = $balx;
	}
}
exit;
while(true){
	$r = curl(host."auto",h())[1];
	if(preg_match('/Cloudflare/',$r)){
		print Error("Cloudflare detect!\n");
		hapus("Cookie");
		goto cookie;
	}
	$tmr = explode(',',explode('let timer = ',$r)[1])[0];
	if($tmr){
		tmr($tmr);
	}else{
		print m."Auto Bonus Error! 1";
		sleep(3);
		print "\r                    \r";
		tmr(120);continue;
	}
	$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
	if(!$token){
		print m."Auto Bonus Error!";
		sleep(3);
		print "\r                    \r";
		tmr(120);continue;
	}
	$data = "token=".$token;
	$r = curl(host."auto/verify",h(),$data)[1];
	sleep(2);
	$r = curl(host."dashboard",h());
	$balx = explode(' Coins</button>',explode('<button type="button" class="button-33 btn-sm">&#128176; ',$r)[1])[0];//3000 Coins
	if($balx > $bal){
		Cetak("Sukses",($balx-$bal)." Coins");
		Cetak("Balance",$balx." Coins");
		print line();
		$bal = $balx;
	}
}
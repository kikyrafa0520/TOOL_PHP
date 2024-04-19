<?php
const
host = "https://adviewsatoshi.com/",
register_link = "https://adviewsatoshi.com/ref/iewilmaestro",
youtube = "https://youtu.be/5PGx-xr7fW0";

function h(){
	$h[] = "host: adviewsatoshi.com";
	$h[] = "user-agent: ".ua();
	$h[] = "cookie: ".simpan("Cookie");
	return $h;
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
ua();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = Curl(host."account",h())[1];
$user = explode('"',explode('https://adviewsatoshi.com/ref/',$r)[1])[0];
$bal= explode('</span>',explode('<span id="balance">',$r)[1])[0];
if(!$user){
	print Error("Session expired".n);
	hapus("Cookie");
	hapus("cookie.txt");
	goto cookie;
}

Cetak("User",$user);
Cetak("Balance",$bal);
print line();
$r = Curl(host."surf",h())[1];
$tot = explode('href="/surf/',$r);
for($i=1;$i<count($tot);$i++){
	$visited = explode('visited-link">',$tot[$i])[1];
	if($visited)continue;
	$id = explode('"',$tot[$i])[0];
	
	$ua1=['Referer:'.host.'surf'];
	$r = Curl(host."surf/".$id,array_merge($ua1,h()))[1];
	
	$tmr = explode(';',explode('let count = ',$r)[1])[0];
	$letid = explode("';",explode("let id = '",$r)[1])[0];
	
	$ua2=['Origin: https://adviewsatoshi.com','Referer: '.host.'/surf/'.$id];
	Curl(host."surf?uid=".$id."&c=".$letid,array_merge($ua2,h()))[1];
	if($tmr){tmr($tmr);}
	
	$ua4=['accept:*/*','x-requested-with:XMLHttpRequest','content-type:application/x-www-form-urlencoded; charset=UTF-8','origin: https://adviewsatoshi.com','sec-fetch-site:same-origin','referer:'.host.'surf/'.$id,'accept-language:id,id-ID;q=0.9,en-US;q=0.8,en;q=0.7'];
	$data = "&uid=".$id."&c=".$letid;
	$r = json_decode(Curl(host."ajax/surf",array_merge($ua4,h()),$data)[1],1);
	if($r["success"]){
		Cetak("Success",explode('successfully!',$r["message"])[0]);
		$r = curl(host."account",h())[1];
		$bal= explode('</span>',explode('<span id="balance">',$r)[1])[0];
		Cetak("Balance",$bal);
		print line();
	}
}
print Error("You have view all ads today!\n");
print line();
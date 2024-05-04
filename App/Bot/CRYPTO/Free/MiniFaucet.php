<?php

require "Modul/TemplateSite/MrqRama.php";

const
register_link = "https://minifaucet.xyz/?r=286",
host = "https://minifaucet.xyz/",
typeCaptcha = "Turnstile",
youtube = "https://youtube.com/@iewil";

Ban(1);

cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
ua();

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = getDashboard();
if(!$r["user"]){
	print Error("Cookie Expired!\n");
	hapus("Cookie");
	goto cookie;
}
Cetak("Username",$r["user"]);
Cetak("Balance",$r["balance"]);
Cetak("Energy",$r["energy"]);
print line();

menu:
Menu(1, "Earn Coin [Free]");
Menu(2, "Faucet + Ptc [Apikey]");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
	getGame("2048-lite","1");
	getGame("pacman-lite","2");
	getGame("hextris-lite","3");
	getGame("taptaptap","4");
	getArticle();
	Getachievements();
	getAutoClaim();
	goto menu;
}elseif($pil == 2){
	if(!$cek_api_input){
		$apikey = MenuApi();
		if(provider_api == "Multibot"){
			$api = New ApiMultibot($apikey);
		}else{
			$api = New ApiXevil($apikey);
		}
		$cek_api_input = 1;
	}
	print line();
	getAds();
	getFaucet();
	goto menu;
}else{
	print Error("Bad Number\n");
	print line();
	goto menu;
}
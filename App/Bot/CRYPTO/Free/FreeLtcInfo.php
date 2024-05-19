<?php

require "App/Template/MrqRama.php";

const
register_link = "https://free-ltc-info.com/?r=4708",
host = "https://free-ltc-info.com/",
typeCaptcha = "Turnstile",
youtube = "https://youtu.be/D5maTlXGCo0";

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
if($r["cloudflare"]){
	print Error("Cloudflare Detect!\n");
	print line();
	hapus("Cookie");
	goto cookie;
}
if(!$r["user"]){
	print Error("Cookie Expired!\n");
	print line();
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
	if(getGame("2048-lite","1")){hapus("Cookie");goto cookie;}
	if(getGame("pacman-lite","2")){hapus("Cookie");goto cookie;}
	if(getGame("hextris-lite","3")){hapus("Cookie");goto cookie;}
	if(getGame("taptaptap","4")){hapus("Cookie");goto cookie;}
	if(getArticle()){hapus("Cookie");goto cookie;}
	if(Getachievements()){hapus("Cookie");goto cookie;}
	if(getAutoClaim()){hapus("Cookie");goto cookie;}
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
	if(getAds()){hapus("Cookie");goto cookie;}
	if(getFaucet()){hapus("Cookie");goto cookie;}
	goto menu;
}else{
	print Error("Bad Number\n");
	print line();
	goto menu;
}
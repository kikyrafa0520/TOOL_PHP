<?php
const
register_link = "https://freebitco.in/?r=48171588",
host = "https://freebitco.in/",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/c/iewil";

function h(){
	$h = ["x-csrf-token: ","sec-ch-ua-mobile: ?1",
	"user-agent: ".ua(),
	"x-requested-with: XMLHttpRequest",
	"save-data: on",
	"origin: ".host,
	"sec-fetch-site: same-origin",
	"sec-fetch-mode: cors",
	"sec-fetch-dest: empty",
	"accept-language: en-US,en;q=0.9,id;q=0.8",
	"cookie: ".simpan("Cookie")];
	return $h;
}
function getCsrf($cookie = "Data/".nama_file."/Cookie") {$r = file_get_contents($cookie);return explode(';',explode('csrf_token=',$r)[1])[0];}
function finger($csrf,$h){
	$rand = num_rand(6);
	$mdrand = md5($rand);
	$data = http_build_query(["op"=> "record_fingerprint","fingerprint"=> $mdrand,"csrf_token" => $csrf]);
	$r = run(host."cgi-bin/api.pl?$data",$h);
	if($r){
		return ["finger"=>$mdrand,"fingernum"=>$rand,"sheed"=>str_rand(16)];
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

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = run(host."/?op=home",h());
$rp = trim(explode('</div>',explode('<div class="reward_table_box br_0_0_5_5 user_reward_points font_bold" style="border-top:none;">',$r)[1])[0]);

$bal = trim(explode('</span>',explode('<span id="balance_small">',$r)[1])[0]);
if(!$bal){
	hapus("Cookie");
	goto cookie;
}
Cetak("Balance",$bal." BTC");
Cetak("Reward",$rp);
Cetak("Bal_Api",$api->getBalance());
print line();

while(true){
	sleep(5);
	$r = run(host."/?op=home",h());
	$cek = GlobalCheck($r);
	if($cek['cf']){
		print Error("Cloudflare Detect\n");
		hapus("Cookie");
		sleep(3);
		print line();
		goto cookie;
	}
	$timer = explode(',',explode("free_play_time_remaining').countdown({until: +",$r)[1])[0];
	if($timer){tmr($timer);}
	$finger = finger(getCsrf(),["user-agent: ".ua()]);
	$sitekey = explode('"',explode('<div class="h-captcha" data-sitekey="',$r)[1])[0];
	if(!$sitekey)continue;
	$cap = $api->Hcaptcha($sitekey, host."/?op=home");
	if(!$cap)continue;
	$data = ["csrf_token"=>getCsrf(),"op"=>"free_play","fingerprint"=>$finger["finger"],"client_seed"=>$finger["sheed"],"fingerprint2"=>$finger["fingernum"],"pwc"=>0,"h_recaptcha_response" =>$cap];
	//$data = ["csrf_token"=>getCsrf(),"op"=>"free_play","fingerprint"=>$finger["finger"],"client_seed"=>$finger["sheed"],"fingerprint2"=>$finger["fingernum"],"pwc"=>1,"h_recaptcha_response" =>""];
	
	$r = run(host, h(),http_build_query($data));
	$x = explode(':',$r);
	if($x[2]){
		Cetak("Number",$x[1]);
		Cetak("You Win",$x[3]." BTC");
		Cetak("Balance",$x[2]." BTC");
		$r = run(host."/?op=home",h());
		$rp = trim(explode('</div>',explode('<div class="reward_table_box br_0_0_5_5 user_reward_points font_bold" style="border-top:none;">',$r)[1])[0]);
		Cetak("Reward",$rp);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else
	if(explode("incorrect",$r)[1]){
		$wr = explode(".",$r)[0];
		print Error($wr);
		sleep(3);
		print "\r                           \r";
	}else{
		exit(m.str_replace(". ","\n",explode(':',$r)[1].n));
	}
}
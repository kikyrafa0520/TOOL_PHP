<?php
const
host = "https://firefaucet.win/",
register_link = "https://firefaucet.win/ref/1258480",
youtube = "https://youtu.be/PHmBubjBneU",
typeCaptcha = "Random";

function Hd(){return ["user-agent: ".ua()];}
function Vision($img){$content=base64_encode($img);$head=["content-type: application/json"];$data=json_encode(["requests"=>[["image"=>["content"=>$content],"features"=>[["type"=>"TEXT_DETECTION"]]]]]);$result= Curl("https://vision.googleapis.com/v1/images:annotate?key=AIzaSyC3y-Em42htSB8UEZPqptJ78rlvL58_h6Y",$head,$data);$capt = explode('"',explode('"Enter the following:\n',$result)[1])[0];if($capt){return preg_replace("/[^a-zA-Z0-9]/","", $capt);}}
function Gsolv($url){$r=curl($url,Hd(),'',1)[1];$ca=explode('"',$r)[5];return $ca;}
function Gmed($ca){$url="https://api-secure.solvemedia.com/papi/media?c=".$ca.";w=300;h=150;fg=000000;bg=f8f8f8";$r=Curl($url,Hd(),'',1)[1];return $r;}

function h(){
	$h = [
	"cookie: ".simpan("Cookie"),
	"user-agent: ".ua()
	];
	return $h;
}
function dash(){
	$r = curl(host,h(),'',1)[1];
	$user = explode('"',explode('https://firefaucet.win/ref/',$r)[1])[0];
	$acp = explode('</b>',explode('<b>',explode('<div style="color:#00a8ff;font-size:3.56rem;text-shadow:1px 2px 2px #1d202b;margin-bottom:10px">',$r)[1])[1])[0];
	return ["user"=>$user,"acp"=>$acp];
}
function balan(){
	$r = curl(host."balance",h(),'',1)[1];
	$x = explode('-usd-balance">',$r);
	foreach($x as $a => $con){
		if($a == 0)continue;
		$bal = strip_tags(explode('<i class="fas fa-info-circle tooltipped"',$con)[0]);
		print h.str_replace(" ~",m."/".h,$bal).n;
	}
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
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

$r = dash();
if(!$r["user"]){
	hapus("Cookie");
	goto cookie;
}
Cetak("Username", $r["user"]);
Cetak("Acp", $r["acp"]);
Cetak("Bal_Api",$api->getBalance());
print line();
balan();
print line();

menu_fire:
Menu(1, "Faucet");
Menu(2, "Visit Ptc");
Menu(3, "Auto Faucet");
Menu(4, "Shortlink");
$pil = readline(Isi("Nomor"));
print line();
if($pil==1){goto faucet;
}elseif($pil==2){goto ptc;
}elseif($pil==3){goto auto;
}elseif($pil==4){goto Shortlink;
}else{echo Error("Bad Number\n");print line();goto menu_fire;}

faucet:
while(true){
	$r = curl(host.'faucet',h(),'',1)[1];
	if(preg_match('/Please come back after/',$r)){
		$r = json_decode(curl(host.'api/additional-details-dashboard/?sidebar=true', h())[1],1);
		tmr(str_replace('m','',$r['faucet_status'])*60);continue;
	}
	$csrf = explode('">',explode('name="csrf_token" value="',$r)[1])[0];
	$activeCaptcha = explode('"',explode('<input name="selected-captcha" type="radio" id="select-',$r)[1])[0];
	Cetak("Captcha",strtoupper($activeCaptcha));
	if($activeCaptcha == 'turnstile'){
		$cap = $api->Turnstile("0x4AAAAAAAEUvFih09RuyAna", host.'faucet');
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data["selected-captcha"] = "turnstile";
		$data["cf-turnstile-response"] = $cap;
	}else
	if($activeCaptcha == 'hcaptcha'){
		$cap = $api->Hcaptcha("034eb992-02f4-4cd7-8f90-5dfb05fb21a2", host.'faucet');
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data["selected-captcha"] = "hcaptcha";
		$data["h-captcha-response"] = $cap;
	}else
	if($activeCaptcha == 'recaptcha'){
		$cap = $api->RecaptchaV2("6LcLRHMUAAAAAImKcp7V9dcmD3ILWPEBJjlFnnrB", host.'faucet');
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data["selected-captcha"] = "recaptcha";
		$data["g-recaptcha-response"] = $cap;
	}else
	if($activeCaptcha == 'solvemedia'){
		$solvemedia = "https://api-secure.solvemedia.com/papi/_challenge.js?k=z59ESC-Y0q8vs9l4gg1yur9HoeNRbisB;f=_ACPuzzleUtil.callbacks%5B0%5D;l=en;t=img;s=300x150;c=js,h5c,h5ct,svg,h5v,v/h264,v/webm,h5a,a/mp3,a/ogg,ua/chrome,ua/chrome116,os/android,os/android10,fwv/Acewiw.lism78,jslib/jquery,htmlplus;am=KsUkaKMseGorApdYoyx4ag;ca=script;ts=1706218734;ct=1706219349;th=custom;r=0.06568499755878143";
		$ca = Gsolv($solvemedia);
		$img = base64_encode(Gmed($ca));
		$cap = $api->Ocr($img);
		
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data["selected-captcha"] = "solvemedia";
		$data["adcopy_response"] = $cap;
		$data["adcopy_challenge"] = $ca;
	}else{
		print Error("No Captcha Detect\n");
		continue;
	}
	$data["csrf_token"] = $csrf;
	
	$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/faucet/"];
	curl(host.'faucet',array_merge(h(),$arr),http_build_query($data),'',1);
	$r = curl(host.'faucet',h(),'',1)[1];
	$wr = explode('</div>',explode('<div class="error_msg hoverable">',$r)[1])[0];
	$ss = strip_tags(explode('</div>',explode('<div class="success_msg hoverable"><b>',$r)[1])[0]);
	if($ss){
		print Sukses($ss);
		Cetak("Acp",dash()["acp"]);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else{
		print Error($wr.n);
		print line();
	}
}
ptc:
while(true){
	$r = curl(host.'ptc',h(),'',1)[1];
	$id = explode("']",explode("_0x727d=['",$r)[1])[0];
	if(!$id){
		print Error("Ptc Habis\n");
		print line();
		goto menu_fire;
	}
	$r = curl(host.'viewptc?id='.$id,h(),'',1)[1];
	$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
	$key = explode("')",explode("onclick=continueptc('",$r)[1])[0];
	$img = explode("'>",explode("<img src='data:image/png;base64, ",$r)[1])[0];
	$tmr = explode("')",explode("parseInt('",$r)[1])[0];
	if($tmr){tmr($tmr);}
	$cap = $api->Ocr($img);
	if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		
	$data = ["captcha"=>$cap,"csrf_token"=>$csrf];
		
	$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/viewptc?id=".$id];
	curl(host."ptcverify?key=".$key."&id=".$id,array_merge($arr,h()),http_build_query($data),'',1);
	$r = curl(host.'ptc',h(),'',1)[1];
	$ss = strip_tags(explode('</b>',explode('<div class="success_msg hoverable">',$r)[1])[0]);
	if($ss){
		print Sukses($ss);
		Cetak("Acp",dash()["acp"]);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}
}

auto:
$r = curl(host,h(),'',1)[1];
$csrf = explode('">', explode('<input type="hidden" name="csrf_token" value="', $r)[1])[0];
$data = 'csrf_token='.$token;
preg_match_all('/<input(.*?)value="([\w]+)"/is',$r,$coins);$a=0;
foreach ($coins[2] as $a => $coin){
	Menu($a, $coin);
	$curency[$a] = $coin;
}
$pilih_coin = readline(Isi("Nomor"));
print line();
$coin_pilih = explode(',',$pilih_coin);
foreach($coin_pilih as $number){
	$data .= "&coins=".$curency[$number];
}

while(true){
	$arr = ["Host: firefaucet.win","cache-control: max-age=0","upgrade-insecure-requests: 1","sec-fetch-dest: document","accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9","referer: https://firefaucet.win/",];
	$r = curl(host."start",array_merge($arr,h()),$data,1)[1];
	tmr(60);
	
	$header = ["Host: firefaucet.win","accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/start"];
	$r = json_decode(curl(host."internal-api/payout/",array_merge($header,h()),'',1)[1],1);
	if($r["success"]==1){
		$coin = array_keys($r["logs"]);
		for($i=0;$i<count($coin);$i++){
			print Sukses("Success ".$r["logs"][$coin[$i]]." ".$coin[$i]);
		}
		Cetak("Acp",dash()["acp"]);
		print line();
	}else{
		print Error($r["message"].n);
		print line();
		goto menu_fire;
	}
	if($r["time_left"] == "0 seconds"){
		print Error("Acp Mencapai batas terendah!\n");
		print line();
		goto menu_fire;
	}
}
Shortlink:

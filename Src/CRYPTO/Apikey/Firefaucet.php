<?php
Exit(Error("progress\n"));
const
host = "https://firefaucet.win/",
register_link = "https://firefaucet.win/ref/1258480",
youtube = "https://youtu.be/PHmBubjBneU";
/*
function Acssi_calvin($string){$acssi = ["a" => ["┌─┐","├─┤","┴ ┴"],"b" => ["┌┐ ","├┴┐","└─┘"],"c" => ["┌─┐","│  ","└─┘"],"d" => ["┌┬┐"," ││","─┴┘"],"e" => ["┌─┐","├┤ ","└─┘"],"f" => ["┌─┐","├┤ ","└  "],"g" => ["┌─┐","│ ┬","└─┘"],"h" => ["┬ ┬","├─┤","┴ ┴"],"i" => ["┬","│","┴"],"j" => [" ┬"," │","└┘"],"k" => ["┬┌─","├┴┐","┴ ┴"],"l" => ["┬  ","│  ","┴─┘"],"m" => ["┌┬┐","│││","┴ ┴"],"n" => ["┌┐┌","│││","┘└┘"],"o" => ["┌─┐","│ │","└─┘"],"p" => ["┌─┐","├─┘","┴  "],"q" => ["┌─┐ ","│─┼┐","└─┘└"],"r" => ["┬─┐","├┬┘","┴└─"],"s" => ["┌─┐","└─┐","└─┘"],"t" => ["┌┬┐"," │ "," ┴ "],"u" => ["┬ ┬","│ │","└─┘"],"v" => ["┬  ┬","└┐┌┘"," └┘ "],"w" => ["┬ ┬","│││","└┴┘"],"x" => ["─┐ ┬","┌┴┬┘","┴ └─"],"y" => ["┬ ┬","└┬┘"," ┴ "],"z" => ["┌─┐","┌─┘","└─┘"]," "=>[" "," "," "],"1" => ["┬","│","┴"],  "2" => ["┌─┐","┌─┘","└─┘"],  "3" => ["┌─┐"," ├┤","└─┘"],"4" => ["┬ ┬","└─┤","  ┘"],"5" => ["┌─┐","└─┐","└─┘"],"6" => ["┌─┐","├─┐","└─┘"],"7" => ["┌─┐","  │","  ┘"],"8" => ["┌─┐","├─┤","└─┘"],"9" => ["┌─┐","└─┤","└─┘"],"0" => ["┌─┐","│ │","└─┘"]];$x = str_split($string);print " ";foreach($x as $data){print b.$acssi[$data][0];}print k." versi ".m.": ".h.versi.n." ";foreach($x as $data){print c.$acssi[$data][1];}print k." status".m.": ".h.status.n." ";foreach($x as $data){print p.$acssi[$data][2];}print "\n";}
function Bn(){system('clear');$m="\033[1;31m";$p="\033[1;37m";$k="\033[1;33m";$h="\033[1;32m";$u="\033[1;35m";$b="\033[1;34m";$c="\033[1;36m";$mp="\033[101m\033[1;37m";$cl="\033[0m";$mm="\033[101m\033[1;31m";$hp="\033[1;7m";print p.str_repeat("─",16).m."> ".h."Scrypt by ".p."iewil ".m."<".p.str_repeat("─",15).n;Acssi_calvin(title);print Line();print "{$mm}[{$mp}▶️{$mm}]{$cl} {$k}https://www.youtube.com/c/iewil\n{$c}{$hp} >_{$cl}{$b} Team-Function-INDO\n{$p}──────────────────────────────────────────────────\n";print m."{$mm}[{$mp}!{$mm}]".$cl.m." SCRIPT GRATIS TIDAK UNTUK DI OBRAL!".n.p."──────────────────────────────────────────────────\n\n";}
function Tmr($tmr){date_default_timezone_set("UTC");$timr = time()+$tmr;$len = 21;while(true){$ran = rand(1,4);$str = c.str_repeat('•',$ran);print "\r                                                  \r";$res=$timr-time();if($res < 1) {break;}print str_repeat(" ",$len-$ran).c.$str.p.date('H:i:s',$res).c.$str;sleep(1);}}
function Simpan($n){if(file_exists($n)) {$d = file_get_contents($n);}else{$d = readline(m."Input ".$n.k." : ".h.n);print n;file_put_contents($n,$d);}return $d;}
function Slow($msg){$slow = str_split($msg);foreach( $slow as $slowmo ){print $slowmo; usleep(70000);}}
function Line(){return u.str_repeat('─',50).n;}
function Hd(){return ["user-agent: ".simpan('User_Agent')];}
function Vision($img){$content=base64_encode($img);$head=["content-type: application/json"];$data=json_encode(["requests"=>[["image"=>["content"=>$content],"features"=>[["type"=>"TEXT_DETECTION"]]]]]);$result= Curl("https://vision.googleapis.com/v1/images:annotate?key=AIzaSyC3y-Em42htSB8UEZPqptJ78rlvL58_h6Y",$head,$data);$capt = explode('"',explode('"Enter the following:\n',$result)[1])[0];if($capt){return preg_replace("/[^a-zA-Z0-9]/","", $capt);}}
function Gsolv($url){$r=curl($url,Hd());$ca=explode('"',$r)[5];return $ca;}
function Gmed($ca){$url="https://api-secure.solvemedia.com/papi/media?c=".$ca.";w=300;h=150;fg=000000;bg=f8f8f8";$r=Curl($url,Hd());return $r;}
function Visionx($img){$content=base64_encode($img);$head=["content-type: application/json"];$data=json_encode(["requests"=>[["image"=>["content"=>$content],"features"=>[["type"=>"TEXT_DETECTION"]]]]]);$result= Curl("https://vision.googleapis.com/v1/images:annotate?key=AIzaSyC3y-Em42htSB8UEZPqptJ78rlvL58_h6Y",$head,$data);$capt = explode('",',explode('"description": "',$result)[1])[0];if(strlen($capt) == 4){return $capt;}}

*/
function h(){
	$h = [
	"cookie: ".simpan("Cookie"),
	"user-agent: ".ua()
	];
	return $h;
}
function dash(){
	$r = curl(host,h())[1];
	$user = explode('"',explode('https://firefaucet.win/ref/',$r)[1])[0];
	$acp = explode('</b>',explode('<b>',explode('<div style="color:#00a8ff;font-size:3.56rem;text-shadow:1px 2px 2px #1d202b;margin-bottom:10px">',$r)[1])[1])[0];
	return ["user"=>$user,"acp"=>$acp];
}
function balan(){
	$r = curl(host."balance",h())[1];
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
$pil = readline(Isi("Nomor"));
print line();
if($pil==1){goto faucet;
}elseif($pil==2){goto ptc;
}elseif($pil==3){goto auto;
}else{echo Error("Bad Number\n");print line();goto menu_fire;}

faucet:
while(true){
	$r = curl(host.'faucet',h())[1];
	if(preg_match('/Please come back/',$r)){
		tmr(1800);continue;
	}
	$csrf = explode('">',explode('name="csrf_token" value="',$r)[1])[0];
	<input name="selected-captcha" type="radio" id="select-hcaptcha"
	$activeCaptcha = explode('"',explode('<input name="selected-captcha" type="radio" id="select-',$r)[1])[0];
	if(activeCaptcha == 'turnstile'){
		$cap = $api->Turnstile("0x4AAAAAAAEUvFih09RuyAna", host.'faucet');
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		$data["selected-captcha"] = "turnstile";
		$data["cf-turnstile-response"] = $cap;
	}
	$data["csrf_token"] = $csrf;
	$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/faucet/"];
	curl(host.'faucet',array_merge(h(),$arr),http_build_query($data),'',1);
	$r = curl(host.'faucet',h())[1];
	$wr = explode('</div>',explode('<div class="error_msg hoverable">',$r)[1])[0];
	$ss = strip_tags(explode('</div>',explode('<div class="success_msg hoverable"><b>',$r)[1])[0]);
	if($ss){
		print h.$ss.n;
		print h."Acp      ".p."-> ".k.dash()["acp"].n;
		print line();
	}else{
		print m.$wr.n;
		print line();
	}
	print $r;exit;
}
ptc:
while(true){
	$r = curl(host.'ptc',h())[1];
	$id = explode("']",explode("_0x727d=['",$r)[1])[0];
	if(!$id){
		print Error("Ptc Habis\n");
		print line();
		goto menu_fire;
	}
	while(true){
		$r = curl(host.'viewptc?id='.$id,h())[1];
		$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
		$key = explode("')",explode("onclick=continueptc('",$r)[1])[0];
		$img = explode("'>",explode("<img src='data:image/png;base64, ",$r)[1])[0];
		$tmr = explode("')",explode("parseInt('",$r)[1])[0];
		if($tmr){tmr($tmr);}
		$cap = $api->Ocr($img);
		if(!$cap){print Error("@".provider_api." Error\n"); continue;}
		
		$data = ["captcha"=>$cap,"csrf_token"=>$csrf];
		print http_build_query($data);exit;
		$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/viewptc?id=".$id];
		curl(host."ptcverify?key=".$key."&id=".$id,array_merge($arr,h()),http_build_query($data),'',1);
		$r = curl(host.'ptc',h())[1];
		$ss = strip_tags(explode('</b>',explode('<div class="success_msg hoverable">',$r)[1])[0]);
		if($ss){
			print "\r                            \r";
			print h.$ss.n;
			print h."Acp      ".p."-> ".k.dash()["acp"].n;
			print line();
			break;
		}
		print $r;exit;
	}
}
auto:
if(file_exists('Cookie_AutoClaim')){
	$cookie = simpan('Cookie_AutoClaim');
}else{
	$cookie = simpan('Cookie_AutoClaim');
	bn();
}
while(true){
	$arr = ["Host: firefaucet.win","cache-control: max-age=0","upgrade-insecure-requests: 1","sec-fetch-dest: document","accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9","referer: https://firefaucet.win/",];
	$r = curl(host."start",array_merge($arr,h()));
	tmr(60);
	
	$header = ["Host: firefaucet.win","accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/start","user-agent: ".simpan('User_Agent'),"cookie: ".$cookie];
	$r = json_decode(curl(host."internal-api/payout/",$header),1);
	if($r["success"]==1){
		$coin = array_keys($r["logs"]);
		for($i=0;$i<count($coin);$i++){
			print h."Success  ".p."-> ".k.$r["logs"][$coin[$i]]." ".$coin[$i].n;
		}
		print h."Acp      ".p."-> ".k.dash()["acp"].n;
		print line();
	}else{
		print m.$r["message"].n;
		print line();
		goto menu_fire;
	}
	if($r["time_left"] == "0 seconds"){
		print m."Acp Mencapai batas terendah!\n";
		print line();
		goto menu_fire;
	}
}
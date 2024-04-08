<?php
exit(error("proses"));
const
host = "https://firefaucet.win/",
register_link = "https://firefaucet.win/ref/1258480",
youtube = "https://youtu.be/PHmBubjBneU";

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

function h(){
	$h = [
	"cookie: ".simpan("Cookie"),
	"user-agent: ".simpan("User_Agent")
	];
	return $h;
}

Bn();
cookie:
simpan("Cookie");
simpan("User_Agent");

Bn();
print slow(p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D");sleep(2);
system("termux-open-url ".youtube);Bn();
function dash(){
	$r = curl(host,h());
	$user = explode('"',explode('https://firefaucet.win/ref/',$r)[1])[0];
	$acp = explode('</b>',explode('<span style="color:#00a8ff;font-size:3.56rem;text-shadow:1px 2px 2px #1d202b"><b>',$r)[1])[0];
	return ["user"=>$user,"acp"=>$acp];
}
function balan(){
	$r = curl(host."balance",h());
	$x = explode('-usd-balance">',$r);
	foreach($x as $a => $con){
		if($a == 0)continue;
		$bal = strip_tags(explode('<i class="fas fa-info-circle tooltipped"',$con)[0]);
		print h.str_replace(" ~",m."/".h,$bal).n;
	}
}

$solvemedia = "https://api-secure.solvemedia.com/papi/_challenge.js?k=z59ESC-Y0q8vs9l4gg1yur9HoeNRbisB;f=_ACPuzzleUtil.callbacks%5B0%5D;l=en;t=img;s=300x150;c=js,h5c,h5ct,svg,h5v,v/h264,v/ogg,v/webm,h5a,a/mp3,a/ogg,ua/chrome,ua/chrome102,os/nt,os/nt10.0,fwv/Budqjw.tedr73,jslib/jquery,htmlplus;am=LV.S.gpk0sMruKcbCmTSww;ca=script;ts=1653739242;ct=1653739904;th=custom;r=0.5477276246015668";

$r = dash();
if(!$r["user"]){
	unlink("Cookie");
	goto cookie;
}
print h."Username ".p."-> ".k.$r["user"].n;
print h."Acp      ".p."-> ".k.$r["acp"].n;
print line();
balan();
print line();

menu:
print m."1-".p." Faucet\n";
print m."2-".p." Visit Ptc\n";
print m."3-".p." Auto Faucet\n";
$pil=readline(h."Input Number ".p.": ".m);
print line();
if($pil==1){goto faucet;
}elseif($pil==2){goto ptc;
}elseif($pil==3){goto auto;
}else{echo m."Bad Number\n";print line();goto menu;}

faucet:
while(true){
	print k."Sabar...";
	$r = curl(host.'faucet',h());
	if(preg_match('/Please come back/',$r)){
		tmr(1800);continue;
	}
	$csrf = explode('">',explode('name="csrf_token" value="',$r)[1])[0];
	
	$ca = Gsolv($solvemedia);
	$img = Gmed($ca);
	$cap = Vision($img);
	if(!$cap){
		print "\r                            \r";
		print k."Sabar......";
		sleep(3);
		print "\r                            \r";
		continue;
	}
	$data = ["csrf_token"=>$csrf,"selected-captcha"=>"solvemedia","adcopy_response"=>$cap,"adcopy_challenge"=>$ca];
	$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/faucet/"];
	curl(host.'faucet',array_merge(h(),$arr),http_build_query($data),1);
	$r = curl(host.'faucet',h());
	$wr = explode(',',explode('<div class="error_msg hoverable">',$r)[1])[0];
	$ss = strip_tags(explode('</div>',explode('<div class="success_msg hoverable"><b>',$r)[1])[0]);
	if($ss){
		print "\r                            \r";
		print h.$ss.n;
		print h."Acp      ".p."-> ".k.dash()["acp"].n;
		print line();
	}else{
		print "\r                            \r";
		print m."Sabar......";
		sleep(3);
		print "\r                            \r";
	}
}
ptc:
while(true){
	$r = curl(host.'ptc',h());
	$id = explode("')",explode("new_ptc('",$r)[1])[0];
	if(!$id){
		print m."Ptc Habis\n";
		print line();
		goto menu;
	}
	while(true){
		$r = curl(host.'viewptc?id='.$id,h());
		$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
		$key = explode("')",explode("onclick=continueptc('",$r)[1])[0];
		$img = explode("'>",explode("<img src='data:image/png;base64, ",$r)[1])[0];
		$tmr = explode("')",explode("parseInt('",$r)[1])[0];
		if($tmr){tmr($tmr);}
		$im = base64_decode(trim($img));
		$cap = Visionx($im);
		
		$data = ["captcha"=>$cap,"csrf_token"=>$csrf];
		$arr = ["Host: firefaucet.win","content-length: ".strlen(http_build_query($data)),"accept: */*","sec-fetch-dest: empty","x-requested-with: XMLHttpRequest","referer: https://firefaucet.win/viewptc?id=".$id];
		curl(host."ptcverify?key=".$key."&id=".$id,array_merge($arr,h()),http_build_query($data),1);
		$r = curl(host.'ptc',h());
		$ss = strip_tags(explode('</b>',explode('<div class="success_msg hoverable">',$r)[1])[0]);
		if($ss){
			print "\r                            \r";
			print h.$ss.n;
			print h."Acp      ".p."-> ".k.dash()["acp"].n;
			print line();
			break;
		}
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
		goto menu;
	}
	if($r["time_left"] == "0 seconds"){
		print m."Acp Mencapai batas terendah!\n";
		print line();
		goto menu;
	}
}
<?php
exit("Script on progres\n");
const
provider_api = "Xevil",
provider_ref = "t.me/Xevil_check_bot?start=6192660395",
register_link = "https://teaserfast.ru/a/iewilmaestro",
host = "https://teaserfast.ru/",
youtube = "https://youtube.com/@iewil",
n = "\n";

function h($xml=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	if($xml){
		$h[] = "X-Requested-With: XMLHttpRequest";
	}
	$h[] = "user-agent: ".ua();
	$h[] = "cookie: ".simpan("Cookie");
	return $h;
}
function cek(){
	$r = curl(host."faucet",h())[1];
}
Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();

$apikey = CheckApi();
$api = new ApiXevil($apikey);

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = curl(host,h())[1];
$user = explode('</div>',explode('<div class="main_user_login">',$r)[1])[0];
$bal= explode('</span>',explode('">',explode('<span class="int blue" id="basic_balance" title="',$r)[1])[1])[0];
if(!$user){
	unlink("Cookie");
	goto cookie;
}

Cetak("Username",$user);
Cetak("Balance",$bal);
print line();

while(true){
	$r = curl(host.'task/',h())[1];
	$ids = explode('<a href="/task/',explode('<div class="it_task task_youtube">',$r)[1]);
	if(!$ids[1])break;
	foreach($ids as $a => $idc){
		if($a == 0)continue;
		$id = explode('">',$idc)[0];
		$r = curl(host.'task/'.$id,h())[1];
		if(preg_match('/Задание не найдено или в данный момент недоступно./',$r)){
			continue;
		}
		$code = explode("'",explode("data: {dt: '",$r)[1])[0];
		$hd = explode("'",explode("hd: '",$r)[1])[0];
		$rc = explode("'",explode(" rc: '",$r)[1])[0];
		$tmr = explode(';',explode('var timercount = ',$r)[1])[0];
		tmr($tmr);
		
		$data = "dt=".$code;
		$r = json_decode(curl(host.'captcha-start/',h(1),$data)[1],1);
		if(!$r['success'])break;
		while(true){
			$data = "yd=$id&hd=$hd&rc=$rc";
			$r = json_decode(curl(host.'captcha-youtube/',h(1),$data)[1],1);
			if(!$r['success'])break;
			$cap = $api->Teaserfast($r['captcha'], $r['small']);
			print $cap.n;
			print urlencode($cap).n;
			
			$data = "crxy=".urlencode($cap)."&dt=".$code;
			$r = json_decode(curl(host.'check-youtube/',h(1),$data)[1],1);
			if($r['captcha']){
				print Error("Oops");
				sleep(2);
				print "\r                 \r";
			}else{
				$desc = $r['desc'];
				if($desc == "Время на прохождение каптчи истекло."){
					break;
				}
				Cetak("Success",$desc);
				$r = curl(host,h())[1];
				$bal= explode('</span>',explode('">',explode('<span class="int blue" id="basic_balance" title="',$r)[1])[1])[0];
				Cetak("Balance",$bal);
				print line();
				break;
			}
		}
	}
}
print Error("Youtube Habis!".n);
print line();
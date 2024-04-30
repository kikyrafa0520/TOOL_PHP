<?php
const
host = "https://tokenmix.pro/",
register_link = "https://tokenmix.pro/?r=Oo8ycF__Ms",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "user-agent: ".ua();
	$h[] = "cookie: ".simpan("Cookie_Autofaucet");
	return $h;
}
function GetDashboard(){
	$r = json_decode(curl(host.'infos/dashboard_info',h(),1)[1],1);
	$data['user'] = ($r['user']['email'])?$r['user']['email']:false;
	$data['balance'] = ($r['user']['coins'])?$r['user']['coins']:false;
	return $data;
}
function GetShortlinks(){
	$shortlinks = new Shortlinks(ApiShortlink());
	$arr = [
	"host: ".parse_url(host)['host'],
	"content-type: application/json",
	"referer: ".host."dashboard/sl"
	];
	$data = '{"page":"sl"}';
	$r = json_decode(curl(host.'infos/auth_page_info',array_merge($arr,h()),$data)[1],1);
	if($r['success']){
		$listshort = $r['sls'];
		foreach($listshort as $a => $short){
			$slid = $short['_id'];
			$slname = $short['name'];
			$jatah = $short['views24Hours'];
			$complet = $short['completed'];
			$cek = $shortlinks->Check($slname);
			if ($cek['status']) {
				for($i = 1; $i <= $jatah; $i ++ ){
					Cetak($slname,$i);
					$data = '{"slId":"'.$slid.'","vote":null,"type":null}';
					$r = curl(host.'user/generateSl',array_merge($arr,h()),$data)[1];
					if($r['success']){
						$shortlink = $r["ShortenedUrl"];
						if(!$shortlink){
							print_r($r);exit;
						}
						$bypas = $shortlinks->Bypass($cek['shortlink_name'], $shortlink);
						$pass = $bypas['url'];
						if($pass){
								tmr($bypas['timer']);
								$r = curl($pass,h());
								print_r($r);exit;
						}
					}else{
						print Error($slname." Finish\n");
						print line();
						break;
					}
				}
				
			}
		}
		exit;
	}
}
function GetFaucet(){
	global $api;
	$arr = [
	"host: ".parse_url(host)['host'],
	"content-type: application/json",
	"referer: ".host."dashboard/faucet"
	];
	tmr(60);
	while(true){
		$data = '{"page":"faucet"}';
		$r = json_decode(curl(host.'infos/auth_page_info',array_merge($arr,h()),$data)[1],1);
		if($r['success']){
			$claims = $r['user_claim']['claims'];
			$maxclaim = $r['faucet']['max_claims'];
			if($claims >= $maxclaim)break;
			$tmr = $r['faucet']['time'];
			
			$cap = $api->RecaptchaV2("6Lc3R50aAAAAAMLC5QwqfXHWmaBa3zxOrTyXxDgT",host."dashboard/faucet");
			if(!$cap)continue;
			
			$data = '{"token":"'.$cap.'","cap":"recaptcha"}';
			$r = json_decode(curl(host.'user/faucet_coins',array_merge($arr,h()),$data)[1],1);
			if($r['success']){
				print Sukses($r['res']);
				Cetak("Coins",$r['user']['coins']);
				Cetak("Bal_Api",$api->getBalance());
				print line();
				tmr($tmr);
			}
		}else{
			print_r($r);
			break;
		}
	}
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie_Autofaucet");
ua();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = GetDashboard();
if(!$r["balance"]){
	print Error("Session expired".n);
	hapus("Cookie_Autofaucet");
	sleep(3);
	print line();
	goto cookie;
}
Cetak("User", $r['user']);
Cetak("Balance",$r["balance"]);
print line();
menu:
Menu(1, "Faucet [Apikey]");
Menu(2, "Autofaucet [No Apikey]");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
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
	GetFaucet();
	goto menu;
}else{
	$r = run(host."user/autofaucet",h());
while(true){
	tmr(60);
	$user = explode('"',explode('t.value="',$r)[1])[0];
	$data = "user=".$user;

	$r = run(host."user/autofaucet",h(),$data);
	if(preg_match('/Cloudflare/',$r)){
		print Error("Cloudflare detect\n");
		print line();
		hapus("Cookie_Autofaucet");
		goto cookie;
	}
	$err=trim(explode('</div>',explode('<div class="AutoACell AAC-error">',$r)[1])[0]);
	if(preg_match('/Insufficient balance to claim rewards/',$r))exit(Error("Insufficient balance to claim rewards\n"));
	
	$coin = explode('</div>',explode('<i class="fas fa-coins"></i>',$r)[1])[0];
	if($coin){
		preg_match('/(-?[1-9]+\\d*([.]\\d+)?)\s(.*)/',$coin,$out);
		print Menu("*",floor($out[1])." ".$out[3]);
	}
	$pay = explode('</a>',explode('<a href="/withdraw" target="_blank">',$r)[1])[0];
	preg_match_all('#<div class="AutoACell AAC-success">(.*?)<a#is',$r,$hasil);
	for($x=0;$x<count($hasil[1]);$x++){
		$has = $hasil[1][$x];
		print Sukses(trim(explode("to",$has)[0]));
	}
	if($coin){
		print line();
	}
}
}
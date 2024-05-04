<?php
const
host = "https://keran.co/",
register_link = "https://keran.co/?ref=4097",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	if($data)$h[] = "Content-Length: ".strlen($data);
	$h[] = "User-Agent: ".ua();
	$h[] = "Cookie: ".simpan("Cookie");
	return $h;
}
function dash(){
	$r = curl(host.'dashboard.php',h(),'',1)[1];
	$user = cfDecodeEmail(explode('"',explode('class="__cf_email__" data-cfemail="',$r)[1])[0]);
	return ["user"=>$user];
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

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = dash();
if(!$r["user"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}

Cetak("Email",$r["user"]);
Cetak("Bal_Api",$api->getBalance());
print line();
menu:
Menu(1,"Claim Faucet");
Menu(2,"Update Cookie");
$pil = readline(Isi("Number"));
print line();
if($pil == 2){
	hapus("Cookie");
	Simpan("Cookie");
	goto cookie;
}
gaslagi:
$r = curl(host.'faucet.php',h())[1];
$cek = GlobalCheck($r);
if($cek['cf']){
	print Error("Cloudflare Detect\n");
	hapus("Cookie");
	print line();
	goto cookie;
}
$list_coin = explode('<form method="post" action="captha.php">',$r);
foreach($list_coin as $a => $coins){
	if($a==0)continue;
	$r = curl(host.'faucet.php',h())[1];
	$list_coin = explode('<form method="post" action="captha.php">',$r)[$a];
	$cek = explode('class="button is-info" disabled>',$list_coin)[1];
	$coint = explode('"',explode('name="claim_crypto" value="',$list_coin)[1])[0];
	if($res){
		if($res[$coint] > 2)continue;
	}
	if($cek){
	    $res = his([$coint=>1],$res);
	    continue;
	}
	$match=[];
	preg_match_all('/(input:?.*?)name=\"(.*?)\" value=\"([^"]+)"/is',$list_coin,$datax);
	for($i = 0;$i<count($datax[2]);$i++){
		$match[$datax[2][$i]] = $datax[3][$i];
	}
	$data = http_build_query($match);
	$r = curl(host.'captha.php',h(),$data)[1];
	$direc = explode('"',explode('<form method="post" action="',$r)[1])[0];
	$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
	preg_match_all('/(input:?.*?)name=\"(.*?)\" value=\"([^"]+)"/is',$r,$datax);
	$match=[];
	for($i = 0;$i<count($datax[2]);$i++){
		$match[$datax[2][$i]] = $datax[3][$i];
	}
	if($sitekey){
		$cap = $api->RecaptchaV2($sitekey, host.'captha.php');
		$match['g-recaptcha-response']=$cap;
	}else{
		$sitekey = explode('"',explode('<div class="cf-turnstile" data-sitekey="',$r)[1])[0];
		if(!$sitekey){print Error("Sitekey Error\n"); continue;}
		$cap = $api->Turnstile($sitekey, host.'captha.php');
		$match['cf-turnstile-response']=$cap;
	}
	if(!$cap)continue;
	$data = http_build_query($match);
	$r = curl(host.$direc,h(),$data)[1];
	if(preg_match('/does not have sufficient/',$r)){
		print c.strtoupper($coint).": ".Error(" The faucet does not have sufficient funds\n");
		$res = his([$coint=>3],$res);
		print line();
	}
	$ss = trim(explode('at ',explode('</div>',explode('<div class="message-body">',$r)[1])[0])[0]);
	$dg = trim(explode('<a',explode('<div class="message-header">',explode('<article class="message is-danger">',$r)[1])[1])[0]);
	$wr = trim(explode('</div>',explode('<div class="message-header">',explode('<article class="message is-warning">',$r)[1])[1])[0]);
	if($ss){
		Cetak($match['claim_crypto'],$ss);
		Cetak("Bal_Api",$api->getBalance());
		$res = his([$coint=>1],$res);
		print line();
	}elseif($dg){
		Cetak($match['claim_crypto'],"Ups");
		print Error($dg.n);
		$res = his([$coint=>1],$res);
		print line();
	}elseif($wr){
	    Cetak($match['claim_crypto'],"Ups");
		print Error($wr.n);
		$res = his([$coint=>1],$res);
		print line();
	}else{
	}
}
Tmr(300);
goto gaslagi;
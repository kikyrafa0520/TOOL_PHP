<?php
const
host = "https://autofaucet.org/",
register_link = "https://autofaucet.org/r/iewilmaestro",
youtube = "https://youtu.be/Zlyir1qEVgU";


function h(){
	$h = [
	"cookie: ".simpan("Cookie"),
	"user-agent: ".ua()
	];
	return $h;
}
function token(){
	$r = curl(host.'dashboard/claim/auto',h())[1];
	$bal=explode('<span>',explode('<p class="amount">',$r)[1])[0];
	return $bal;
}
function AutofctOrg_rata($str){
	$len = strlen($str);
	$strlen = 6;
	$lenstr = strtoupper($str).str_repeat(" ",$strlen-$len).p.": ";
	return $lenstr;
}
function AutofctOrg_rata2($str){
	$len = strlen($str);
	$strlen = 16;
	$lenstr = $str.str_repeat(" ",$strlen-$len).c."│";
	return $lenstr;
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

$r = curl(host."dashboard",h())[1];
$user = explode('</p>',explode('<p class="username">',$r)[1])[0];
if(!$user){
	hapus("Cookie");
	goto cookie;
}
Cetak("Username",$user);
Cetak("Token", token());
print line();
menu:
Menu(1, "Check Balance");
Menu(2, "Auto Faucet");
$pil = readline(Isi("Number"));
print line();

if($pil == 1){
	goto check;
}elseif($pil == 2){
	goto auto;
}
check:
$r = curl(host.'dashboard',h())[1];
$list_bal = explode('<div class="col details">',explode('<div class="row balances">',$r)[1]);
foreach($list_bal as $num => $bal){
	if($num==0)continue;
	$coin_name = explode('</span>',explode('<span>',$bal)[1])[0];
	$coin_bal = str_replace("\n","",explode('<span>',explode('<p class="amount">',$bal)[1])[0]);
	if( $num % 2 == 0){
		print h.AutofctOrg_rata($coin_name).k.trim($coin_bal).n;
	}else{
		print h.AutofctOrg_rata($coin_name).k.AutofctOrg_rata2(trim($coin_bal));
	}
}
print n;
print line();
goto menu;

auto:
if(file_exists("Data/".nama_file."/autofaucet")){
	Menu(1,"Data Lama");
	Menu(2,"Data Baru");
	$jol = readline(Isi("Number"));
	print line();
	if($jol == 2){
		hapus("autofaucet");
		goto baru;
	}
	$data = file_get_contents("Data/".nama_file."/autofaucet");
	goto lewat;
}
baru:
$r = curl(host.'dashboard/claim/auto',h())[1];
$cur = explode('id="auto-currency-select" value="',$r);
$pay = explode('<label class="rwrapper">',explode('</fieldset>',explode('<fieldset id="payout">',$r)[1])[0]);
$freq = explode('<label class="rwrapper">',explode('</fieldset>',explode('<fieldset id="frequency">',$r)[1])[0]);
$bost = explode('<label class="rwrapper">',explode('</fieldset>',explode('<fieldset id="boost">',$r)[1])[0]);

foreach ($cur as $cr => $curen){
	if($cr==0)continue;
	$strlen = 6;
	$crnum = 2;
	$coin = explode('">',$curen)[0];
	$len = strlen($coin);
	$crlen = strlen($cr);
	$crc = $cr.str_repeat(" ",$crnum-$crlen);
	if($cr % 4 == 0){
		print h."[".p.$crc.h."]-".k.$coin.n;
	}else{
		$lenstr = $coin.str_repeat(" ",$strlen-$len);
		print h."[".p.$crc.h."]-".k.$lenstr;
	}
	$curency[$cr] = $coin;
}
print n;
$pilih_coin = readline(Isi("Number Coin"));
print line();
$coin_pilih = explode(',',$pilih_coin);
foreach($coin_pilih as $number){
	$data .= "currencies%5B%5D=".$curency[$number]."&";
}
//payout
foreach($pay as $numpay => $payou){
	if($numpay==0)continue;
	$select = explode("\n",$payou)[0];
	$bonus = explode('</div>',explode('<div class="info-s">',$payou)[1])[0];
	$val = explode('">',explode('name="payout" value="',$payou)[1])[0];//1
	print h."[".p.$numpay.h."]-".k.$select;
	if($bonus){
		print c.$bonus;
	}
	print n;
	$payout[$numpay] = $val;
}
print n;
$pilih_pay = readline(Isi("Number Payout"));
print line();
$data .= "payout=".$payout[$pilih_pay]."&";

foreach($freq as $numfreq => $frequ){
	if($numfreq==0)continue;
	$select = explode("\n",$frequ)[0];
	$val = explode('">',explode('id="auto-frequency-radio" value="',$frequ)[1])[0];
	$bonus = explode('</div>',explode('<div class="info-s">',$frequ)[1])[0];
	print h."[".p.$numfreq.h."]-".k.$select;
	if($bonus){
		print c.$bonus;
	}
	print n;
	$frequency[$numfreq] = $val;
	$waktu[$numfreq] = explode(" ",$select)[0]*60;
}
print n;
$pilih_freq = readline(Isi("Number Frequency"));
print line();
$timer = $waktu[$pilih_freq];
$data .= "frequency=".$frequency[$pilih_freq]."&";
//boost
foreach($bost as $numbos => $bostd){
	if($numbos==0)continue;
	$select = explode("\n",$bostd)[0];
	$val = explode('">',explode('id="auto-boost-radio" value="',$bostd)[1])[0];
	$bonus = explode('</div>',explode('<div class="info-s">',$bostd)[1])[0];
	print h."[".p.$numbos.h."]-".k.$select;
	if($bonus){
		print c.$bonus;
	}
	print n;
	$boost[$numbos] = $val;
}
print n;
$pilih_bost = readline(Isi("Number Boost"));
print line();
$data .= "boost=".$boost[$pilih_bost];

lewat:
while(true){
	$arr = ["x-requested-with: XMLHttpRequest"];
	$r = curl(host."verify/cli-au",array_merge($arr,h()),$data)[1];
	if(explode("will be started",$r)[1] && !file_exists("Data/".nama_file."/autofaucet")){
		file_put_contents("Data/".nama_file."/autofaucet",$data);
	}
	$r = curl(host.'dashboard/claim/auto/start',h())[1];
	if(preg_match('/Cloudflare/',$r)){
		print Error("Cloudflare Detect\n");
		print line();
		hapus("Cookie");
		goto cookie;
	}
	$warn=explode('</p>',explode('<p class="warning">',$r)[1])[0];
	if($warn){
		if(preg_match('/insufficient FCT token balance/',$r)){
			$warn = str_replace('! ',"!\n",$warn);
			print Error($warn.n);
			print line();exit;
		}
		if(preg_match('/Please wait until time is up!/',$r)){
			$warn = str_replace('! ',"!\n",$warn);
			print Error($warn.n);
			print line();
		}
	}
	preg_match_all('#<p class="message"><i class="fas fa-check green"></i>(.*?)</p>#is',$r,$has);
	if($has[1]){
		for($i=0;$i<count($has[1]);$i++){
			print Sukses($has[1][$i]);
		}
		print Cetak("Token",token());
		print line();
	}
	$r = curl(host.'dashboard/claim/auto/start',h())[1];
	$tm = trim(strip_tags(explode('</span>',explode('Next payout in',$r)[1])[0]));
	if($tm){
		$minut=explode('m',$tm);
		if(strpos($minut[0],'s')==''){
			$tmr=$minut[0]*60;
			if($minut[1]){
				$tmr=$tmr+trim(str_replace('s','',$minut[1]));
			}
			if($tmr){
				tmr($tmr);
			}
		}else{
			$tmr=trim(str_replace('s','',$minut[0]));
			if($tmr){
				tmr($tmr);
			}
		}
	}
}
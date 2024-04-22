<?php
const
host = "https://autoums.online/",
register_link = "https://autoums.online/?r=3197",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	$h[] = "Host: autoums.online";
	$h[] = "X-Requested-With: XMLHttpRequest";
	$h[] = "user-agent: ".ua();
	return $h;
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Email_Faucetpay");
ua();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

if(!file_exists("Data/".nama_file."/cookie.txt")){
	loginagain:
	$r = curl(register_link,h(),'',1)[1];
	//$r = curl(host,h(),'',1)[1];
	$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
	$data = [
		"wallet" => simpan("Email_Faucetpay"),
		"csrf_token_name" => $csrf
	];
	
	$r = curl(host."auth/login",h(),http_build_query($data),1)[1];
	$ss = explode("',",explode("html: '",$r)[1])[0];
	if($ss){
		Cetak("INFO",$ss);print line();sleep(5);
		Ban(1);
	}else{
		print Error("Sepertinya akun di banned\n");
		hapus("cookie.txt");
		goto loginagain;
	}
}
Ban(1);
$r = curl(host,h(),'',1)[1];
if(!explode('Logout',$r)[1]){
	hapus("cookie.txt");
	goto loginagain;
}
Cetak("Wallet: ",simpan("Email_Faucetpay"));
print line();
menu:
Menu(1,"No timer");
Menu(2,"Normal");
$mode = readline(Isi("Nomor"));
print line();
while(true){
$r = curl(host,h(),'',1)[1];
$con = explode('/faucet/currency/',$r);
	foreach($con as $a => $coins){
		if($a == 0)continue;
		$coin = explode('"',$coins)[0];
		$r = curl(host."faucet/currency/".$coin,h(),'',1)[1];
		if(preg_match('/Please confirm your email address to be able to claim or withdraw/',$r)){
			print Error("Please confirm your email address to be able to claim or withdraw\n");
			print line();
			exit;
		}
		if($res && count($res) == count($con)-1){
			$check = $res[$coin];
			if($check < 5)continue;
		}
		if(preg_match("/You don't have enough energy for Auto Faucet!/",$r)){
			exit(m."You don't have enough energy for Auto Faucet!\n");
		}
		if(preg_match('/Daily claim limit/',$r)){
			$res = his([$coin=>1],$res);
			Cetak($coin,"Daily claim limit");
			continue;
		}
		$status_bal = explode('</span>',explode('<span class="badge badge-danger">',$r)[1])[0];
		if($status_bal == "Empty"){
			$res = his([$coin=>1],$res);
			Cetak($coin,"Sufficient funds");
			continue;
		}
		$tmr = explode(',',explode('let timer = ',$r)[1])[0];
		if($mode==2){
			tmr($tmr);
		}
		$auto = explode('"',explode('<input type="hidden" name="auto_faucet_token" value="',$r)[1])[0];
		$csrf = explode('"',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
		$token = explode('"',explode('<input type="hidden" name="token" value="',$r)[1])[0];
		
		$data = "auto_faucet_token=".$auto."&csrf_token_name=".$csrf."&token=".$token;
		$r = curl(host."faucet/verify/".$coin,h(),$data,1)[1];
		$ss = explode("account!'",explode("html: '0.",$r)[1])[0];
		$wr = explode("'",explode("html: '",$r)[1])[0];
		$ban = explode('</div>',explode('<div class="alert text-center alert-danger"><i class="fas fa-exclamation-circle"></i> Your account',$r)[1])[0];
		
		if($ss){
		    $res = his([$coin=>10],$res);
			print Sukses("0.".str_replace("has been sent ","",$ss));
		}elseif($wr){
		    $res = his([$coin=>10],$res);
			print Error(substr($wr,0,30));
			sleep(3);
			print "\r                  \r";
		}elseif($ban){
		    exit(Error("Your account".$ban.n));
		}else{
			print Error("Server Down");
			sleep(3);
			print "\r                  \r";
		}
	}
	if(max($res) < 5)break;
}

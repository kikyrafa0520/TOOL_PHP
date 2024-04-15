<?php
const
host = "https://ourinfo.top/",
register_link = "https://ourinfo.top/?r=2378",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	//$h[] = "Host: ourinfo.top";
	$h[] = "user-agent: ".ua();
	$h[] = "Cookie: ".Simpan("Cookie");
	return $h;
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
/*
if(!file_exists("Data/".nama_file."/cookie.txt")){
	loginagain:
	$r = curl(register_link,h(),'',1)[1];
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
		hapus("cookie.txt");
		goto loginagain;
	}
}
Ban(1);
*/
$r = curl(host,h())[1];
if(!explode('Logout',$r)[1]){
	hapus("cookie.txt");
	hapus("Cookie");
	goto cookie;
}
//Cetak("Wallet: ",simpan("Email_Faucetpay"));
//print line();

$con = explode('/faucet/currency/',$r);
$num = 0;
while(true){
	$cecker = curl(host,h())[1];
	if(!explode('Logout',$cecker)[1]){
		hapus("cookie.txt");
		hapus("Cookie");
		goto cookie;
	}
	foreach($con as $a => $coins){
		if($a == 0)continue;
		$coin = explode('"',$coins)[0];
		$r = curl(host."faucet/currency/".$coin,h())[1];
		$cek = GlobalCheck($r);
		if($cek['cf']){
			print Error("Cloudflare Detect\n");
			hapus("cookie.txt");
			hapus("Cookie");
			goto cookie;
		}
		if(preg_match('/Daily claim limit/',$r)){
			exit(Error("Daily claim limit for this coin reached\n"));
		}
		$auto = explode('">',explode('<input type="hidden" name="auto_faucet_token" value="',$r)[1])[0];
		$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
		$hiden = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];
		$left = explode('</p>',explode('<p class="lh-1 mb-1 font-weight-bold">',$r)[3])[0];
		if(explode("/",$left)[0] == "0"){
			exit(Error("Daily claim limit for this coin reached\n"));
		}
		$tmr = explode(",",explode('let timer = ',$r)[1])[0];
		if($tmr){
			tmr($tmr);
		}
		$data = "auto_faucet_token=".$auto."&csrf_token_name=".$csrf."&token=".$hiden;
		$r = curl(host."faucet/verify/".$coin,h(),$data)[1];
		print_r($r);exit;
		$ss = explode("account!',",explode("html: '0.",$r)[1])[0];
		$wr = explode(".",explode("html: '",$r)[1])[0];
		if($ss){
			print Sukses("0.".str_replace("has been sent ","",$ss));
		}elseif($wr){
			print Error(substr($wr,0,30));
			sleep(3);
			print "\r                  \r";
		}else{
			print Error("Server Down");
			sleep(3);
			print "\r                  \r";
		}
	}
}

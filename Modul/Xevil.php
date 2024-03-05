<?php

function Xevil_Api(){
	$api = Simpan_Api("Xevil_Apikey");
	Xevil_Bal();
	print Sukses(h."---OK\n");
	sleep(3);
}
function Xevil_Bal(){
	$apikey = Simpan_Api("Xevil_Apikey");
	$url = "http://goodxevilpay.pp.ua/";
	$x = json_decode(file_get_contents($url."res.php?action=userinfo&key=".$apikey),1);
	if(!$x["balance"]){
		exit(Error("Apikey: ".m."Saldo Apikey habis!".n));
	}
	print Cetak("Bal_Api",$x["balance"]." Rub");
}
function Xevil_Hc($sitekey, $pageurl){
	$apikey = Simpan_Api("Xevil_Apikey");
	$url = "http://goodxevilpay.pp.ua/";
	$r =  json_decode(file_get_contents($url."in.php?key=".$apikey."&method=hcaptcha&sitekey=".$sitekey."&pageurl=".$pageurl."&json=1"),1);
	$status = $r["status"];
	if($status == 0){
		print(b."Apikey: ".m.$r["request"].n);
		return 0;
	}
	$id = $r["request"];
	while(true){
		print "prosess...";
		$r = json_decode(file_get_contents($url."res.php?key=".$apikey."&action=get&id=".$id."&json=1"),1);
		$status = $r["status"];
		if($r["request"] == "CAPCHA_NOT_READY"){
			echo "\r                      \r";
			print "prosess......";
			sleep(10);
			print "\r                    \r";
			continue;
		}
		if($status == 1){
			print "\r                 \r";
			return $r["request"];
		}
		return 0;
	}
}
function Xevil_Rv2($sitekey, $pageurl){
	$apikey = Simpan_Api("Xevil_Apikey");
	$url = "http://goodxevilpay.pp.ua/";
	$r =  json_decode(file_get_contents($url."in.php?key=".$apikey."&method=userrecaptcha&googlekey=".$sitekey."&pageurl=".$pageurl."&json=1"),1);
	$status = $r["status"];
	if($status == 0){
		print(b."Apikey: ".m.$r["request"].n);
		return 0;
	}
	$id = $r["request"];
	while(true){
		print "prosess...";
		$r = json_decode(file_get_contents($url."res.php?key=".$apikey."&action=get&id=".$id."&json=1"),1);
		$status = $r["status"];
		if($r["request"] == "CAPCHA_NOT_READY"){
			echo "\r                      \r";
			print "prosess......";
			sleep(10);
			print "\r                    \r";
			continue;
		}
		if($status == 1){
			print "\r                 \r";
			return $r["request"];
		}
		return 0;
	}
}
function Xevil_Ocr($img){
	$apikey = Simpan_Api("Xevil_Apikey");
	$url = "http://goodxevilpay.pp.ua/";
	$ua = "Content-type: application/x-www-form-urlencoded";
	$data = "key=".$apikey."&method=base64&body=".$img."&json=1";
	$opts = ['http' =>['method'  => 'POST','header' => $ua,'content' => $data]];
	$r = json_decode(file_get_contents($url.'in.php', false, stream_context_create($opts)),1);
	$status = $r["status"];
	if($status == 0){
		print("Apikey: ".m.$r["request"].n);
		return 0;
	}
	$id = $r["request"];
	while(true){
		print "prosess...";
		$r = json_decode(file_get_contents($url."res.php?key=".$apikey."&action=get&id=".$id."&json=1"),1);
		$status = $r["status"];
		if($r["request"] == "CAPCHA_NOT_READY"){
			echo "\r                      \r";
			print "prosess......";
			sleep(10);
			print "\r                    \r";
			continue;
		}
		if($status == 1){
			print "\r                 \r";
			return $r["request"];
		}
		return 0;
	}
}
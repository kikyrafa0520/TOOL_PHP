<?php
function Multibot_Api(){
	Cetak("Register","https://multibot.in/");
	$api = Simpan_Api("Multibot_Apikey");
	Multibot_Bal();
	print Sukses(h."---OK\n");
	sleep(3);
}
function Multibot_Bal(){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
	$x = json_decode(file_get_contents($url."res.php?action=userinfo&key=".$apikey),1);
	if(!$x["balance"]){
		unlink("Data/Apikey/Multibot_Apikey");
		exit(Error("Apikey: ".m."Saldo Apikey habis!".n));
	}
	print Cetak("Bal_Api",$x["balance"]." Token");
}
function Multibot_Hc($sitekey, $pageurl){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
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
function Multibot_Rv2($sitekey, $pageurl){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
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
function Multibot_Ocr($img){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
	$data = ["key"=>$apikey,"method"=>"universal","body" => $img,"json" => true];
	$opts = ['http' =>['method'  => 'POST','content' => http_build_query($data)]];
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
function Multibot_Atb($source){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
	$main = explode('"',explode('<img src="',explode('Bot links',$source)[1])[1])[0];
	$antiBot["main"] = $main;
	$src = explode('rel=\"',$source);
	foreach($src as $x => $sour){
		if($x == 0)continue;
		$no = explode('\"',$sour)[0];
		$img = explode('\"',explode('<img src=\"',$sour)[1])[0];
		$antiBot[$no] = $img;
	}
	$ua = "Content-type: application/x-www-form-urlencoded";
	$data = ["key"=>$apikey,"method"=>"antibot","json"=>1] + $antiBot;
	$opts = ['http' =>['method'  => 'POST','header' => $ua,'content' => http_build_query($data)]];
	
	$r = json_decode(file_get_contents($url.'in.php', false, stream_context_create($opts)),1);
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
			return "+".str_replace(",","+",$r["request"]);
			
		}
		return 0;
	}
}
function Multibot_Turnstile($sitekey, $pageurl){
	$apikey = Simpan_Api("Multibot_Apikey");
	$url = "http://api.multibot.in/";
	$r =  json_decode(file_get_contents($url."in.php?key=".$apikey."&method=turnstile&sitekey=".$sitekey."&pageurl=".$pageurl."&json=1"),1);
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
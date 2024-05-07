<?php

Class Captcha{
	static function icon(){
		$data["apikey"] = "iewil";
		$data["methode"] = "icon";
		$cap = json_decode(curl(host.'system/libs/captcha/request.php',h(1),"cID=0&rT=1&tM=light")[1],1);
		foreach($cap as $c){
			$data[$c] = base64_encode(curl(host.'system/libs/captcha/request.php?cid=0&hash='.$c, h(0,1))[1]);
		}
		$data = http_build_query($data);
		$cap = json_decode(curl("https://reward-all-game.000webhostapp.com/api/res.php",0,$data)[1],1);
		if(!$cap['status'])return 0;
		$res=curl(host.'system/libs/captcha/request.php',h(1),"cID=0&pC=".$cap['result']."&rT=2",'',1)[1];
		return $cap['result'];
	}
	static function gp($src){
		$data["apikey"] = "iewil";
		$data["methode"] = "gp";
		$data["main"] = base64_encode($src);
		$data = http_build_query($data);
		$cap = json_decode(curl("https://reward-all-game.000webhostapp.com/api/res.php",0,$data)[1],1);
		if(!$cap['status'])return 0;
		return $cap;
	}
}
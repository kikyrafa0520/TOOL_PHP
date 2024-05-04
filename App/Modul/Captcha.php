<?php

Class Captcha{
	static function index($data_array) {
		for ($i = 0; $i < count($data_array); $i++) {
			$current_size = $data_array[$i];
			$is_duplicate = false;
			for ($j = 0; $j < count($data_array); $j++) {
				if ($i != $j && $current_size == $data_array[$j]) {
					$is_duplicate = true;
					break;
				}
			}
			if (!$is_duplicate) {
				return $i;
			}
		}
		return -1; 
	}
	static function icon(){
		$cap = json_decode(curl(host.'system/libs/captcha/request.php',h(1),"cID=0&rT=1&tM=light")[1],1);
		foreach($cap as $c){
			$im[] = strlen(base64_encode(curl(host.'system/libs/captcha/request.php?cid=0&hash='.$c, h(0,1))[1]));
		}
		$no = self::index($im);
		$res=curl(host.'system/libs/captcha/request.php',h(1),"cID=0&pC=".$cap[$no]."&rT=2",'',1)[1];
		return $cap[$no];
	}
}

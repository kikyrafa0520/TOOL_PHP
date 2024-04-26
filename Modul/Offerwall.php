<?php
Class Offerwall {
	function __construct($host, $apikey){
		$this->host = $host;
		if(provider_api == "Multibot"){
			$this->api = New ApiMultibot($apikey);
		}else{
			$this->api = New ApiXevil($apikey);
		}
	}
	function check($nama){
		print k."--[".p."?".k."] ".p."check offerwall";
		$check = strtolower($nama);
		$supported = [
			"excentiv" => "excentiv"
		];
		sleep(2);
		$filter = $supported[$check];
		if($filter){
			print "\r                              \r";
			print h."[".p."√".h."] support offerwall";
			sleep(2);
			print "\r                              \r";
			return ["status" => 1,"offerwall_name" => $filter];
		}else{
			print "\r                              \r";
			print m."[".p."!".m."] not support offerwall";
			sleep(2);
			print "\r                              \r";
			return ["status" => 0,"message" => "not supported offerwall"];
		}
	}
	function Excentiv_header($_host, $ref = 0){
		$h[] = 'host: '.$_host;
		$h[] = 'user-agent: '.ua();
		$h[] = 'X-Requested-With: XMLHttpRequest';
		if($ref)$h[] = 'referer: https://coins-battle.com/';
		return $h;
	}
	function Excentiv(){
		while(1){
			$r = curl($this->host."excentiv",h("excentiv.com"))[1];
			$iframe = explode('"',explode('<iframe src="',$r)[1])[0];
			if(!$iframe){
				return ["status" => 0,"message" => "iframe lose"];
			}
			$r = curl($iframe, Excentiv_header(),'',1)[1];
			$val = explode('"',explode('<button value="',$r)[1])[0];
			if(!$val)break;
			$r = curl($val, h("coins-battle.com"))[1];
			
		}
		return ["status" => 0,"message" => "game finish"];
	}
	function Offerwall(){
		
	}
}
?>
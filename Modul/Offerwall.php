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
			print "\r                                     \r";
			print h."[".p."√".h."] $check support offerwall";
			sleep(2);
			print "\r                                     \r";
			return ["status" => 1,"offerwall_name" => $filter];
		}else{
			print "\r                                     \r";
			print m."[".p."!".m."] $check not support offerwall";
			sleep(2);
			print "\r                                     \r";
			return ["status" => 0,"message" => "not supported offerwall"];
		}
	}
	function Excentiv_header($_host, $_ref = 0){
		$h[] = 'host: '.$_host;
		$h[] = 'user-agent: '.ua();
		$h[] = 'X-Requested-With: XMLHttpRequest';
		if($_ref)$h[] = 'referer: '.$_ref;
		return $h;
	}
	function Excentiv(){
		hapus("cookie.txt");
		while(1){
			$r = curl($this->host."excentiv",h())[1];
			$iframe = explode('"',explode('<iframe src="',$r)[1])[0];
			if(!$iframe){
				return ["status" => 0,"message" => "iframe lose"];
			}
			$r = curl($iframe, $this->Excentiv_header("excentiv.com"),'',1)[1];
			$val = explode('"',explode('<button value="',$r)[1])[0];
			if(!$val)break;
			$r = curl($val, $this->Excentiv_header("coins-battle.com"),'',1)[1];
			$id = explode('"',explode('game/play/',$r)[rand(1,32)])[0];
			$r = curl('https://coins-battle.com/game/play/'.$id, $this->Excentiv_header("coins-battle.com"),'',1)[1];
			$tmr = explode("'",explode("let ctimer = '",$r)[1])[0];
			$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
			if($tmr)Tmr($tmr);
			$cap = $this->api->RecaptchaV2("6LdQN2wkAAAAAJcsc6u8xgog6ObX0icCRAowGiW8",'https://coins-battle.com/game/play/'.$id);
			if(!$cap)continue;
			$data = "game_id=".$id."&csrf_token=".$csrf."&captcha=recaptchav2&g-recaptcha-response=".$cap;
			$r = curl('https://coins-battle.com/game/claimreward',$this->Excentiv_header("coins-battle.com","https://coins-battle.com/"),$data,1)[1];
			$ss = explode(', to',explode('<i class="fa fa-check-circle"></i> ',$r)[1])[0];
			if(preg_match('/Great/',$ss)){
				Cetak('Game id', $id);
				print Sukses($ss);
				Cetak("Bal_Api",$this->api->getBalance());
				print line();
			}
		}
		return ["status" => 0,"message" => "game finish"];
	}
	function Offers4crypto(){
		hapus("cookie.txt");
		while(1){
			
		}
	}
	function Offerwall($offer){
		$cek = $this->check($offer);
		if(!$cek['status'])return 0;
		$offerwall_name = $cek['offerwall_name'];
		if($offerwall_name == "excentiv"){
			$this->Excentiv();
		}
	}
}
?>
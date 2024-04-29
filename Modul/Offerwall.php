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
			$id = explode('"',explode('game/play/',$r)[rand(1,20)])[0];
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
				Cetak("Balance",GetDashboard()["balance"]);
				print line();
			}
		}
		return ["status" => 0,"message" => "game finish"];
	}
	function Offers4crypto(){
		hapus("cookie.txt");
		$r = curl($this->host."offers4crypto",h())[1];
		$link_key = 'https://offerwall.me/offerwall/'.explode('"',explode('offerwall.me/offerwall/',$r)[1])[0];
		
		$ua = [
		"host: offerwall.me",
		"user-agent: ".ua(),
		"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
		];
		
		$hd = [
		"host: 123games.site",
		"user-agent: ".ua(),
		"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
		];
		
		while(1){
			$r = curl($link_key, $ua,'',1)[1];
			$token = explode("'",explode("var token = '",$r)[1])[0];
			$data = "type=games&token=".$token."&action=switch_cat";
			$r = curl($link_key, $ua, $data,1)[1];
			$data = "token=".$token."&action=start_gs";
			$r = curl($link_key, $ua, $data,1)[1];
			if(preg_match('/Session Created!/',$r)){
				$link = str_replace('\/','/',explode("'",explode("window.open('",$r)[1])[0]);
				$r = curl($link, $hd,'',1)[1];
				preg_match_all('#<a href="https://'.parse_url($link)['host'].'/game/(.*?)">#',$r,$games);
				$game = $games[1][rand(0,count($games[1])-1)];
				while(true){
					$r = curl("https://123games.site/game/".$game, $hd, '',1)[1];
					$key = explode('&',explode('game.php?key=',$r)[1])[0];
					$tmr = explode(';',explode('var timer = ',$r)[1])[0];
					
					$data = "action=start_game";
					$r = curl("https://123games.site/game/".$game, $hd, $data,1)[1];
					if(trim($r) == 'ok'){
						tmr($tmr);
						$r = curl("https://offerwall.me/game.php?key=$key&game_name=Moto%20X3M:%20Spooky%20Land",  $ua, '', 1);
						
						$data = "action=verify";
						$r = json_decode(curl("https://offerwall.me/game.php?key=$key&game_name=Moto%20X3M:%20Spooky%20Land", $ua, $data, 1)[1],1);
						if($r['valid']){
							Cetak('Game id', $game);
							print Sukses($r['msg']);
							Cetak("Balance",GetDashboard()["balance"]);
							print line();
						}else{
							return ["status" => 0,"message" => $r['msg']];
						}
					}else{
						return ["status" => 0,"message" => "games.site has change"];
					}
				}
			}else{
				return ["status" => 0,"message" => "invalid session"];
			}
		}
		
	}
}
?>
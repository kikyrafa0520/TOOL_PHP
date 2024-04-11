<?php
Class RequestApi{
	function in_api($content, $method, $header = 0){
		$param = "key=".$this->apikey."&json=1&".$content;
		if($method == "GET")return json_decode(file_get_contents($this->host.'/in.php?'.$param),1);
		$opts['http']['method'] = $method;
		if($header) $opts['http']['header'] = $header;
		$opts['http']['content'] = $param;
		return json_decode(file_get_contents($this->host.'/in.php', false, stream_context_create($opts)),1);
	}
	function res_api($api_id){
		$params = "?key=".$this->apikey."&action=get&id=".$api_id."&json=1";
		return json_decode(file_get_contents($this->host."/res.php".$params),1);
	}
	function getBalance(){
		$res =  json_decode(file_get_contents($this->host."/res.php?action=userinfo&key=".$this->apikey),1);
		return $res["balance"];
	}
	function wait($xr,$tmr){
		if($xr < 50){$wr=h;}elseif($xr >= 50 && $xr < 80){$wr=k;}else{$wr=m;}
		$xwr = [$wr,p,$wr,p];
		$sym = [' ─ ',' / ',' │ ',' \ ',];
		$a = 0;
		for($i=$tmr*4;$i>0;$i--){
			print $xwr[$a % 4]." bypass $xr%".$sym[$a % 4]." \r";
			usleep(100000);
			if($xr < 99)$xr+=1;
			$a++;
		}
		return $xr;
	}
	function getResult($data ,$method, $header = 0){
		$get_in = $this->in_api($data ,$method, $header);
		if(!$get_in["status"]){
			print $get_in["request"]."\n";
			return 0;
		}
		$a = 0;
		while(true){
			echo " bypass $a% |   \r";
			$get_res = $this->res_api($get_in["request"]);
			if($get_res["request"] == "CAPCHA_NOT_READY"){
				$ran = rand(5,10);
				$a+=$ran;
				if($a>99)$a=99;
				echo " bypass $a% ─ \r";
				$a = $this->wait($a,5);
				continue;
			}
			if($get_res["status"]){
				echo " bypass 100%";
				sleep(1);
				print "\r                     \r";
				print h."[".p."√".h."] bypass success";
				sleep(2);
				print "\r                     \r";
				return $get_res["request"];
			}
			print m."[".p."!".m."] bypass failed";
			sleep(2);
			print "\r                     \r";
			return 0;
		}
	}
}
Class ApiMultibot extends RequestApi {
	public $apikey;
	
	function __construct($apikey){
		$this->host = "http://api.multibot.in";
		$this->provider = "multibot";
		$this->apikey = $apikey;
	}
	function RecaptchaV2($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "userrecaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Hcaptcha($sitekey, $pageurl ){
		$data = http_build_query([
			"method" => "hcaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Turnstile($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "turnstile",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Ocr($img){
		$data = http_build_query([
			"method" => "universal",
			"body" => $img
			]);
		return $this->getResult($data, "POST");
	}
	function AntiBot($source){
		$main = explode('"',explode('src="',explode('Bot links',$source)[1])[1])[0];
		if(!$main)return 0;
		$data["method"] = "antibot";
		$data["main"] = $main;
		$src = explode('rel=\"',$source);
		foreach($src as $x => $sour){
			if($x == 0)continue;
			$no = explode('\"',$sour)[0];
			$img = explode('\"',explode('src=\"',$sour)[1])[0];
			$data[$no] = $img;
		}
		$data = http_build_query($data);
		//print_r($data);
		$ua = "Content-type: application/x-www-form-urlencoded";
		$res = $this->getResult($data, "POST", $ua);
		return "+".str_replace(",","+",$res);
	}
}
Class ApiXevil extends RequestApi {
	//public $apikey;
	
	function __construct($apikey){
		$this->host = "https://sctg.xyz";
		$this->apikey = $apikey."|SOFTID6192660395";
	}
	function RecaptchaV2($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "userrecaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Hcaptcha($sitekey, $pageurl ){
		$data = http_build_query([
			"method" => "hcaptcha",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Turnstile($sitekey, $pageurl){
		$data = http_build_query([
			"method" => "turnstile",
			"sitekey" => $sitekey,
			"pageurl" => $pageurl
			]);
		return $this->getResult($data, "GET");
	}
	function Ocr($img){
		$data = "method=base64&body=".$img;
		//$ua = "Content-type: application/x-www-form-urlencoded";
		return $this->getResult($data, "POST");
	}
	function AntiBot($source){
		$main = explode('"',explode('data:image/png;base64,',explode('Bot links',$source)[1])[1])[0];
		if(!$main)return 0;
		$data = "key=".$this->apikey."&json=1&method=antibot&main=$main";
		$src = explode('rel=\"',$source);
		foreach($src as $x => $sour){
			if($x == 0)continue;
			$no = explode('\"',$sour)[0];
			$img = explode('\"',explode('data:image/png;base64,',$sour)[1])[0];
			$data .= "&$no=$img";
		}
		$res = $this->getResult($data, "POST");
		if($res)return "+".str_replace(",","+",$res);
		return 0;
	}
	function Teaserfast($main, $small){
		$data = http_build_query([
			"method" => "teaserfast",
			"main_photo" => $main,
			"task" => $small
		]);
		//$data = "method=teaserfast&main_photo=".$main."&task=".$small;
		return $this->getResult($data, "GET");
	}
}

/*************************** APIKEY ***************************/
function Simpan_Api($nama_data){
	if(file_exists("Data/Apikey/".$nama_data)){
		$data = file_get_contents("Data/Apikey/".$nama_data);
	}else{
		if(!file_exists("Data/Apikey")){
			system("mkdir Apikey");
			if(PHP_OS_FAMILY == "Windows"){
				system("move Apikey Data");
			}else{
				system("mv Apikey Data");
			}
			print Sukses(h."Berhasil membuat Folder untuk ".k."Apikey".n);
		}
		$data = readline(Isi($nama_data));echo "\n";
		file_put_contents("Data/Apikey/".$nama_data,$data);
	}
	return $data;
}
function CheckApi(){
	Cetak("Register",provider_ref);
	$apikey = Simpan_Api(provider_api."_Apikey");
	if(provider_api == "Xevil"){
		$api = New ApiXevil($apikey);
	}
	if(provider_api == "Multibot"){
		$api = New ApiMultibot($apikey);
	}
	if($api->getBalance()){
		print Sukses(h."OK\n");
		sleep(3);
		return $apikey;
	}else{
		unlink("Data/Apikey/".provider_api."_Apikey");
		exit(Error("Apikey: ".m."Something wrong!".n));
	}
}
function MenuApi(){
	Cetak("Captcha",typeCaptcha);
	if(typeCaptcha == "hcaptcha"){
		$multi = p."[".k."required".p."]";
		$xevil = "";
	}else{
		$multi = "";
		$xevil = p."[".k."required".p."]";
	}
	Menu(1, "Multibot $multi");
	Menu(2, "Xevil $xevil");
	$pil = readline(Isi("Provider Apikey"));
	print line();
	if($pil == 1){
		define("provider_api","Multibot");
		define("provider_ref", "http://api.multibot.in/");
		$apikey = CheckApi();
	}elseif($pil == 2){
		define("provider_api","Xevil");
		define("provider_ref", "t.me/Xevil_check_bot?start=6192660395");
		$apikey = CheckApi();
	}else{
		exit(Error("Tolol\n"));
	}
	return $apikey;
}
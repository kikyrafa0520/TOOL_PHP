<?php
function Curl($u, $h = 0, $p = 0,$cookie = 0, $lewat = 0) {
	while(true){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $u);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_COOKIE,TRUE);
		if($cookie) {
			curl_setopt($ch, CURLOPT_COOKIEFILE,"Data/".nama_file."/cookie.txt");
			curl_setopt($ch, CURLOPT_COOKIEJAR,"Data/".nama_file."/cookie.txt");
		}
		if($p) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
		}
		if($h) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
		}
		curl_setopt($ch, CURLOPT_HEADER, true);
		$r = curl_exec($ch);
		if($lewat){
			return 0;
		}
		$c = curl_getinfo($ch);
		if(!$c) return "Curl Error : ".curl_error($ch); else{
			$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			curl_close($ch);
			if(!$bd){
				print Error("Check your Connection!");
				sleep(2);
				print "\r                         \r";
				continue;
			}
			return array($hd,$bd);
		}
	}
}
function Xevil_Hc($sitekey, $pageurl){
	$apikey = "VbprTCu9FMdZBknXFH60gPICTf7z0Lcu";
	$url = "http://goodxevilpay.pp.ua/";
	$r =  json_decode(file_get_contents($url."in.php?key=".$apikey."&method=hcaptcha&sitekey=".$sitekey."&pageurl=".$pageurl."&json=1"),1);
	$status = $r["status"];
	if($status == 0){
		print("Apikey: ".$r["request"]);
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
		print "\r                 \r";
		return 0;
	}
}
$h = [
"Cookie: _ref=11405; _ga=GA1.1.109194778.1710910555; _session=1ee890f27d7b4ef8b45572a2c2093ae025754b64734beaaad221dad76cb6b9039462d14e54407f7b0fa8a770af7ce4909b1e042b55963bfcb53a2c409b5ea916e776b1994c2386c824cef209e2809e169a8f%26id%3D1; _ga_ZK08VLQJLJ=GS1.1.1710989750.4.1.1710989750.0.0.0
User-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36"
];

$r = Curl("https://polybox.finance/dashboard",$h)[1];
$js = explode(']',explode('const data = [',$r)[1])[0];
$chal = explode('"',explode('algorithm:"SHA-256",challenge:"',$js)[1])[0];
$salt = explode('"',explode('salt:"',$js)[1])[0];
$signatur = explode('"',explode('signature:"',$js)[1])[0];
$superform = explode('"',explode('harvestForm:{id:"',$js)[1])[0];
$datax = '{"algorithm":"SHA-256","challenge":"'.$chal.'","number":18563,"salt":"'.$salt.'","signature":"'.$signatur.'","took":2802}';
$alcha = base64_encode($datax);

$cap = Xevil_Hc("1e370d89-9700-4681-b770-8e5f3e841ced", "https://polybox.finance/dashboard");

$data['hcaptcha'] = $cap;
$data['altcha'] = $alcha;
$data['__superform_id'] = $superform;

$r = curl("https://polybox.finance/dashboard?/harvest",$h,$data)[1];
print_r($r);
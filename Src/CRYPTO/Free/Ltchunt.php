<?php
const
host = "https://ltchunt.com/",
register_link = "https://ltchunt.com/?ref=2925",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h($xml = 0, $img = 0){
	$h[]	= "Host: ".parse_url(host)['host'];
	if($xml){
		$h[]	= "X-Requested-With: XMLHttpRequest";
	}
	if($img){
        $h[] = "accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8";
    }
	$h[]	= "cookie: ".Simpan("Cookie");
	$h[]	= "user-agent: ".ua();
	return $h;
}
function GetDashboard(){
	$r = curl(host.'account.html', h())[1];
	$data['user'] = explode('</font>', explode('<font class="text-success">', $r)[1])[0];
	$data['balance'] = explode('</b>', explode('Account Balance <div class="text-primary"><b>', $r)[1])[0];
	$data['value'] = explode('</b>', explode('Value <div class="text-warning"><b>', $r)[1])[0];
	return $data;
}
function index($data_array) {
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
function icon(){
    $cap = json_decode(curl(host.'system/libs/captcha/request.php',h(1),"cID=0&rT=1&tM=light")[1],1);
    foreach($cap as $c){
		$im[] = strlen(base64_encode(curl(host.'system/libs/captcha/request.php?cid=0&hash='.$c, h(0,1))[1]));
	}
	$no = index($im);
	$res=curl(host.'system/libs/captcha/request.php',h(1),"cID=0&pC=".$cap[$no]."&rT=2",'',1)[1];
	return $cap[$no];
}

function getPtc(){
	Title("Ptc");
	while(true){
		$r = curl(host.'ptc.html',h())[1];
		$id = explode('">', explode('<div class="website_block" id="', $r)[1])[0];
		$key = explode("',", explode("&key=", $r)[1])[0];
		if(!$id)break;
		
		$r = curl(host.'surf.php?sid='.$id.'&key='.$key,h())[1];
		if (preg_match('/Session expired!/', $r)) {
			print Error("ession expired!\n");
			print line();
			return 1;
		}
		
		$token = explode("';", explode("var token = '", $r)[1])[0];
		$tmr = explode(";", explode('var secs = ', $r)[1])[0];
		tmr($tmr);
		
		$cap = @Captcha::icon();
		$data = "a=proccessPTC&data=".$id."&token=".$token."&captcha-idhf=0&captcha-hf=".$cap;
		$r = json_decode(curl(host.'system/ajax.php', h(1), $data)[1], 1);
		if ($r['status'] == 200) {
			print Sukses(trim(strip_tags($r['message'])));
			$r = GetDashboard();
			Cetak("Balance",$r["balance"].'-'.$r["value"]);
			print line();
		}
	}
	print Error("Ptc has finished\n");
	print line();
	
}
function getShortlinks(){
	$shortlinks = new Shortlinks(ApiShortlink());
	Title("Shortlinks");
	$r = curl(host."shortlinks.html",h())[1];
	$list = explode('<tr>',$r);
	foreach($list as $a => $short){
		if($a <= 1)continue;
		$short_name = explode('.',explode('</td>',explode('<td class="align-middle">',$short)[1])[0])[0];//shortsfly
		$id = explode("'",explode("goShortlink('",$r)[1])[0];
		$limit = explode('/',explode('<b class="badge badge-dark">',$r)[1])[0];
		$cek = $shortlinks->Check($short_name);
		if ($cek['status']) {
			for($i = 1; $i <= $limit; $i ++ ){
				$r = curl(host."shortlinks.html",h())[1];
				$token = explode("'",explode("var token = '",$r)[1])[0];
				Cetak($short_name,$i);
				$cap = @Captcha::icon();
				$data = "a=getShortlink&data=".$id."&token=".$token."&captcha-idhf=0&captcha-hf=".$cap;
				$r = json_decode(Curl(host.'system/ajax.php',h(),$data)[1],1);
				if($r["status"] == 200){
					$shortlink = $r["shortlink"];
					$bypas = $shortlinks->Bypass($cek['shortlink_name'], $shortlink);
					$pass = $bypas['url'];
					if($pass){
						tmr($bypas['timer']);
						$r = curl($pass,h())[1];
						$ss = explode('</div>',explode('<div class="alert alert-success mt-0" role="alert">',$r)[1])[0];
						if($ss){
							print Sukses($ss);
							$r = GetDashboard();
							Cetak("Balance",$r["balance"].'-'.$r["value"]);
							Cetak("SL_Api",$bypas['balance']);
							print line();
						}
					}
				}
			}
		}
	}
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
if(!Simpan("Cookie"))print "\n".line();
if(!ua())print "\n".line();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);
$r = GetDashboard();
if(!$r["user"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}
Cetak("Username",$r["user"]);
Cetak("Balance",$r["balance"].'-'.$r["value"]);
print line();
menu:
Menu(1, "Ptc");
Menu(2, "Shortlinks");
$pil = readline(Isi("Number"));
print line();
if($pil == 1){
	getPtc();
	goto menu;
}elseif($pil == 2){
	getShortlinks();
	goto menu;
}else{
	print Error("Bad Number\n");
	print line();
	goto menu;
}

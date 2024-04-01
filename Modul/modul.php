<?php
const
n = "\n",
name = "iewil",
author = "iewilmaestro",
author_email = "<purna.iera@gmail.com>";

if( PHP_OS_FAMILY == "Linux" ){
	define("b","\033[1;34m");
	define("c","\033[1;36m");
	define("d","\033[0m");
	define("h","\033[1;32m");
	define("k","\033[1;33m");
	define("m","\033[1;31m");
	define("p","\033[1;37m");
	define("u","\033[1;35m");
	define("mp","\033[101m\033[1;37m");
	define("pm","\033[107m\033[1;31m");
	define("ph","\033[107m\033[1;30m");
	define("bp","\033[104m\033[1;37m");
} else {
	define("b","");
	define("c","");
	define("d","");
	define("h","");
	define("k","");
	define("m","");
	define("p","");
	define("u","");
	define("mp","");
	define("pm","");
	define("ph","");
	define("bp","");
}
function replace_txt($msg){
	$awal = ["[","]","+","-",">","*"];
	$akhir =[h."[",h."]".p,h."+",m."-",m.">".p,k."*"];
	return str_replace($awal,$akhir,$msg);
}
/*************PRINT**************/
function menu($no, $title){
	print h."---[".p."$no".h."] ".k."$title\n";
}
function Error($except = "[No Content]"){
	return m."---[".p."!".m."] ".p.$except;
}
function Isi($msg){
	return m."╭[".p."Input ".$msg.m."]".n.m."╰> ".h;
}
function Sukses($msg = "[No Content]"){
	return h."---[".p."✓".h."] ".p.$msg.n;
}
function Cetak($label, $msg = "[No Content]"){
	$len = 9;
	$lenstr = $len-strlen($label);
	print h."[".p.$label.h.str_repeat(" ",$lenstr)."]─> ".p.$msg.n;
}
function Line(){
	return b.str_repeat('─',50).n;
}
function Simpan($nama_data){
	if(file_exists("Data/".nama_file."/".$nama_data)){
		$data = file_get_contents("Data/".nama_file."/".$nama_data);
	}else{
		if(!file_exists("Data/".nama_file)){
			system("mkdir ".nama_file);
			if(PHP_OS_FAMILY == "Windows"){
				system("move ".nama_file." Data");
			}else{
				system("mv ".nama_file." Data");
			}
			print Sukses(h."Berhasil membuat Folder untuk ".k.nama_file.n);
		}
		$data = readline(Isi($nama_data));echo "\n";
		file_put_contents("Data/".nama_file."/".$nama_data,$data);
	}
	return $data;
}
function ua(){
	$nama_data = "User_Agent";
	if(file_exists('Data/'.$nama_data)){
		$data = file_get_contents('Data/'.$nama_data);
	}else{
		$data = readline(Isi($nama_data));echo "\n";
		file_put_contents('Data/'.$nama_data,$data);
	}
	return $data;
}
function Hapus($nama_data){
	unlink("Data/".nama_file."/".$nama_data);
}

/************Banner****************/
function TimeZone(){
	system("clear");
	$check = json_decode(file_get_contents("setup.php"),1);
	print b."───────────".m."[".p."scrypt by ".h."iewil".m."]─>".k." v ".$check['version'].n;
	$api = json_decode(file_get_contents("http://ip-api.com/json"),1);
	if($api){
		$tz = $api["timezone"];
		date_default_timezone_set($tz);
		print k.date("d/M/Y").m."-".k.date("H:i:s").n;
		print k.$api['city'].m.",".k.$api['regionName'].m.",".k.$api['country'].n;
	}else{
		date_default_timezone_set("UTC");
		return "UTC";
	}
	print b."Channel".m.": ".p."t.me/Tool_php".m." >".n;
	print b."Insta  ".m.": ".p."instagram.com/iewil_13".m." >".n;
	print b."Youtube".m.": ".p."youtube.com/@iewil".m." >".n;
	print line();
}
function Ban($sc = 0){
	TimeZone();
	print m."~~~~~\t┌┬┐ ┌─┐ ┌─┐ ┬    ┬─┐ ┬ ┬ ┬─┐\t ~~~~~".n;
	print m."~~~~~\t │  │ │ │ │ │    ├─┘ ├─┤ ├─┘\t ~~~~~".n;
	print p."~~~~~\t ┴  └─┘ └─┘ ┴─┘  ┴   ┴ ┴ ┴ \t ~~~~~".n;
	if($sc){
		print Line();
		print h."Sc Aktif: ".k.strtoupper(nama_file).n;
		print Line();
	}else{
		print Line();
	}
}
function run($url, $ua, $data = null,$cookie=null) {
	while(true){
		$ch = curl_init();curl_setopt_array($ch, array(CURLOPT_URL => $url,CURLOPT_FOLLOWLOCATION => 1,));
		if ($data) {
			curl_setopt_array($ch, array(CURLOPT_POST => 1,CURLOPT_POSTFIELDS => $data,));}
		curl_setopt_array($ch, array(CURLOPT_HTTPHEADER => $ua,CURLOPT_SSL_VERIFYPEER => 1,CURLOPT_RETURNTRANSFER => 1,CURLOPT_ENCODING => 'gzip'));
		if ($cookie) {
			curl_setopt_array($ch, array(CURLOPT_COOKIEFILE=>"cookie.txt", CURLOPT_COOKIEJAR=>"cookie.txt",));}
		$run = curl_exec($ch);curl_close($ch);
		if(!$run){
			print "\r                                       \r";
			print Error("Check your Connection!");
			sleep(2);
			print "\r                                       \r";
			continue;
		}
		return $run;
	}
}
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
function Auth($w){
	$lo[] = $w."L".p."oading....";
	$lo[] = p."L".$w."o".p."ading....";
	$lo[] = p."Lo".$w."a".p."ding....";
	$lo[] = p."Loa".$w."d".p."ing....";
	$lo[] = p."Load".$w."i".p."ng....";
	$lo[] = p."Loadi".$w."n".p."g....";
	$lo[] = p."Loadin".$w."g".p."....";
	$lo[] = p."Loading".$w.".".p."...";
	$lo[] = p."Loading.".$w.".".p."..";
	$lo[] = p."Loading..".$w.".".p.".";
	return $lo;
}
function Tmr($tmr){
	date_default_timezone_set("UTC");
	$col = [b,c,d,h,k,m,u];
	$sym = [' ─ ',' / ',' │ ',' \ ',];
	$timr = time()+$tmr;
	$a = 0;
	while(true){
		$a +=1;
		$x = $col[array_rand($col)];
		$nic = auth($x);
			
		$res=$timr-time();
		if($res < 1) {
			break;
		}
		print "         ".$x.$sym[$a % 4].p.date('H',$res).$x.":".p.date('i',$res).$x.":".p.date('s',$res)." ".$nic[$a % count($nic)]."\r";
		usleep(100000);
	}
	print "\r                                   \r";
}

function num_rand($int){
	$rand_num = "1234567890";
	$split = str_split($rand_num);
	$res = "";while(true){
		$rand = array_rand($split);
		$res .= $split[$rand];
		if( strlen($res) == $int ){ 
			return $res; 
		}
	}
}
function str_rand($int){
	$rand_str = "abcdefghijklmnopqrstuvwqyz";
	$rand_num = "1234567890";
	$rand_str_up= "ABCDEFGHIJKLMNOPQRSTUVWQYZ";
	$split = str_split($rand_str.$rand_num.$rand_str_up);
	$res = "";while(true){
		$rand = array_rand($split);
		$res .= $split[$rand];
		if( strlen($res) == $int ){
			return $res;
		}
	}
}
function His($newdata,$data=0){
	if(!$data){
		$data = [];
	}
	return array_merge($data,$newdata);
}
function cfDecodeEmail($encodedString){
  $k = hexdec(substr($encodedString,0,2));
  for($i=2,$email='';$i<strlen($encodedString)-1;$i+=2){
    $email.=chr(hexdec(substr($encodedString,$i,2))^$k);
  }
  return $email;
}
function clean($str){
	return str_replace(['<?','<?php','?>'], ' ',$str);
}

function repatch($str){
	str_replace('.php','',$str);
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
/********SL********/
function _Fly($url){
	$scheme = parse_url($url)['scheme'].'://';
	$host = parse_url($url)['host'];
    $path = parse_url($url)['path'];
    $context = stream_context_create(['http' => ['header' => ['origin: https://advertisingexcel.com', 'referer: https://advertisingexcel.com/outgoing/']]]);
    $res_head = get_headers($scheme.$host.'/flyinc.'.$path, true, $context);
    $final = $res_head["location"];
    return $final;
}



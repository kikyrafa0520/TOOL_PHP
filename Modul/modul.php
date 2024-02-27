<?php
const
n = "\n";

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

function menu($no, $title){
	print h."---[".p."$no".h."] ".k."$title\n";
}
function Error($except){
	return m."---[".p."!".m."] ".p.$except;
}
function Isi($msg){
	return k."---[".p."?".k."] ".p.$msg;
}
function Sukses($msg){
	return h."---[".p."✓".h."] ".p.$msg;
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
			print(h."Berhasil membuat Folder untuk ".k.nama_file.n);
		}
		$data = readline(c."---[".p."+".c."] ".p."Input ".$nama_data.": ".h);echo "\n";
		file_put_contents("Data/".nama_file."/".$nama_data,$data);
	}
	return $data;
}
function Hapus($nama_file, $nama_data){
	unlink("Data/".nama_file."/".$nama_data);
}
function Ban($sc = 0){
	TimeZone();
	print m."\t___ ____ ____ _       ___  _  _ ___ ".n;
	print m."\t |  |  | |  | |       |__] |__| |__] ".n;
	print p."\t |  |__| |__| |___    |    |  | |  ".n;
	if($sc){
		print Line();
		print h."Sc Aktif: ".k.strtoupper(nama_file).n;
		print Line();
	}else{
		print Line();
	}
}
function Line(){
	return b.str_repeat('─',50).n;
}
function run($url, $ua, $data = null,$cookie=null) {
	while(true){
		$ch = curl_init();curl_setopt_array($ch, array(CURLOPT_URL => $url,CURLOPT_FOLLOWLOCATION => 1,));
		if ($data) {
			curl_setopt_array($ch, array(CURLOPT_POST => 1,CURLOPT_POSTFIELDS => $data,));}
		curl_setopt_array($ch, array(CURLOPT_HTTPHEADER => $ua,CURLOPT_SSL_VERIFYPEER => 1,CURLOPT_RETURNTRANSFER => 1,CURLOPT_ENCODING => 'gzip',));
		if ($cookie) {
			curl_setopt_array($ch, array(CURLOPT_COOKIEFILE=>"cookie.txt", CURLOPT_COOKIEJAR=>"cookie.txt",));}
		$run = curl_exec($ch);curl_close($ch);
		if(!$run){
			print "\r                                       \r";
			print m."Check your Connection!";
			sleep(2);
			print "\r                                       \r";
			continue;
		}
		return $run;
	}
}
function Cetak($label, $msg){
	$len = 9;
	$lenstr = $len-strlen($label);
	print h."[".p.$label.h.str_repeat(" ",$lenstr)."]─> ".p.$msg.n;
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
function TimeZone(){
	system("clear");
	print b."───────────".m."[".p."scrypt by ".h."iewil".m."]─>".n;
	$api = json_decode(file_get_contents("http://ip-api.com/json"),1);
	if($api){
		$tz = $api["timezone"];
		date_default_timezone_set($tz);
		print k.date("d/M/Y").m."-".k.date("H:i:s").n;
		print k.$api['city'].m.",".k.$api['regionName'].m.",".k.$api['country'].n;
		print line();
	}else{
		date_default_timezone_set("UTC");
		return "UTC";
	}
}

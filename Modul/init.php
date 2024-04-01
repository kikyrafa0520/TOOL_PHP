<?php
if(!file_exists("Data")){system("mkdir Data");}
if(file_exists("User_Agent")){$nama_file = "User_Agent";if(PHP_OS_FAMILY == "Windows"){system("move ".$nama_file." Data");}else{system("mv ".$nama_file." Data");}}

require "Modul/Apikey.php";
require "Modul/Lisensi.php";

Ban();
$r = json_decode(file_get_contents("https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/setup.php"),1);
$version = $r['version'];
$versi = $check['version'];

$a = 0;
sleep(3);
if($versi !== $version){
	print Error("Latest Version: ".k.$version.n);
	print line();
	$x = file_get_contents("https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/Modul/Update.txt");
	print h."[+] ".p."= add".m.", ".h."[".k."*".h."] ".p."= edit".m.", ".h."[".m."-".h."] ".p."= remove\n\n";
	print replace_txt($x).n;
	print line();
	Menu($a+=1,"Update Versi");
	$tam[$a] = "update";
}else{
	print Sukses("Latest Version: ".k.$version);
	print line();
}
if(file_exists("Data/Apikey/Multibot_Apikey")){
	Menu($a+=1,"Hapus APi Multibot");
	$tam[$a] = "multi";
}
if(file_exists("Data/Apikey/Xevil_Apikey")){
	Menu($a+=1,"Hapus APi Xevil");
	$tam[$a] = "xevil";
}
if($a > 0){
	Menu($a+=1,"Skip");
	$tam[$a] = "skip";
}
if($tam){
	$pil = readline(Isi("Nomor"));
	if($pil == '' || $pil >= Count($tam)+1)exit(Error("Tolol"));
	if($tam[$pil] == "update"){
		system("git reset --hard");
		system("git pull");
		sleep(3);
	}elseif($tam[$pil] == "multi"){
		unlink("Data/Apikey/Multibot_Apikey");
		print "Berhasil Menghapus Apikey Multibot";
		sleep(3);
	}elseif($tam[$pil] == "xevil"){
		unlink("Data/Apikey/Xevil_Apikey");
		print "Berhasil Menghapus Apikey Xevil";
		sleep(3);
	}else{
	}
	Ban();
}

$r = scandir("Src");$a = 0;
foreach($r as $act){
	if($act == '.' || $act == '..') continue;
	$menu[$a] =  $act;
	Menu($a, $act);
	$a++;
}
$pil = readline(Isi("Nomor"));
print line();
if($pil == '' || $pil >= Count($menu))exit(Error("Tolol"));

$r = scandir("Src/".$menu[$pil]);$a = 0;
foreach($r as $act){
	if($act == '.' || $act == '..') continue;
	$menu2[$a] =  $act;
	Menu($a, $act);
	$a++;
}
$pil2 = readline(Isi("Nomor"));
print line();
if($pil2 == '' || $pil2 >= Count($menu2))exit(Error("Tolol"));
if(explode('-',$menu2[$pil2])[1])exit(Error("Tolol"));
$is_file = is_file("Src/".$menu[$pil]."/".$menu2[$pil2]);
if($is_file){
	define("nama_file",$menu2[$pil2]);
	Ban(1);
	eval(file_get_contents("Src/".$menu[$pil]."/".$menu2[$pil2]));
	exit;
}

$r = scandir("Src/".$menu[$pil]."/".$menu2[$pil2]);$a=0;
foreach($r as $act){
	if($act == '.' || $act == '..') continue;
	$menu3[$a] =  $act;
	Menu($a, $act);
	$a++;
}
$pil3 = readline(Isi("Nomor"));
print line();
if($pil3 == '' || $pil3 >= Count($menu3))exit(Error("Tolol"));
if(explode('-',$menu3[$pil3])[1])exit(Error("Tolol"));

define("nama_file",$menu3[$pil3]);
Ban(1);
eval(file_get_contents("Src/".$menu[$pil]."/".$menu2[$pil2]."/".$menu3[$pil3]));

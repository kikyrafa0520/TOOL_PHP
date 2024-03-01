<?php
error_reporting(0);
if(!file_exists("Data")){system("mkdir Data");}
//eval(file_get_contents("Modul/modul"));
require "Modul/modul.php";
Ban();
require "Exception.php";
$r = json_decode(file_get_contents("https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/setup.php"),1);
$version = $r['version'];
$versi = $check['version'];
if($versi < $version)
	system("git pull");

$r = scandir("Src");$a = 0;
foreach($r as $act){
	if($act == '.' || $act == '..') continue;
	$menu[$a] =  $act;
	Menu($a, $act);
	$a++;
}
$pil = readline(Isi("Pilih Nomor: "));
print line();
if($pil == '' || $pil >= Count($menu))exit(Error("Tolol"));

$r = scandir("Src/".$menu[$pil]);$a = 0;
foreach($r as $act){
	if($act == '.' || $act == '..') continue;
	$menu2[$a] =  $act;
	Menu($a, $act);
	$a++;
}
$pil2 = readline(Isi("Pilih Nomor: "));
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
$pil3 = readline(Isi("Pilih Nomor: "));
print line();
if($pil3 == '' || $pil3 >= Count($menu3))exit(Error("Tolol"));
if(explode('-',$menu3[$pil3])[1])exit(Error("Tolol"));

define("nama_file",$menu3[$pil3]);
Ban(1);
eval(file_get_contents("Src/".$menu[$pil]."/".$menu2[$pil2]."/".$menu3[$pil3]));
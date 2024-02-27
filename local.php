<?php
//error_reporting(0);

if(!file_exists("Data")){system("mkdir Data");}
//eval(file_get_contents("Modul/modul"));
require "Modul/modul.php";
Ban();
$r = scandir("Src");
foreach($r as $a => $act){
	$menu[$a] =  $act;
	Menu($a, $act);
}
$pil = readline(Isi("Pilih Nomor: "));
print line();
if($pil == '' || $pil == Count($r))exit(Error("Tolol"));

$r = scandir("Src/".$menu[$pil]);
foreach($r as $a => $act){
	$menu2[$a] =  $act;
	Menu($a, $act);
}
$pil2 = readline(Isi("Pilih Nomor: "));
print line();
if($pil2 == '' || $pil2 == Count($r))exit(Error("Tolol"));

$is_file = is_file("Src/".$menu[$pil]."/".$menu2[$pil2]);
if($is_file){
	define("nama_file",$menu2[$pil2]);
	Ban(1);
	eval(file_get_contents("Src/".$menu[$pil]."/".$menu2[$pil2]));
	exit;
}

$r = scandir("Src/".$menu[$pil]."/".$menu2[$pil2]);
foreach($r as $a => $act){
	$menu3[$a] =  $act;
	Menu($a, $act);
}
$pil3 = readline(Isi("Pilih Nomor: "));
print line();
if($pil3 == '' || $pil3 == Count($r))exit(Error("Tolol"));
define("nama_file",$menu3[$pil3]);
Ban(1);
eval(file_get_contents("Src/".$menu[$pil]."/".$menu2[$pil2]."/".$menu3[$pil3]));
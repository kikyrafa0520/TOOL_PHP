<?php
error_reporting(0);
function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    if(is_dir($dir))rmdir($dir);
	else unlink($dir);
}
$dir = scandir(realpath(__DIR__));
$file = pathinfo(__FILE__, PATHINFO_FILENAME).".php";
foreach($dir as $a => $activ_dir){
	if($activ_dir == "." || $activ_dir == ".." || $activ_dir == "Data" || $activ_dir == $file)continue;
	rrmdir($activ_dir);
}
if(!file_exists("Data")){system("mkdir Data");}
eval(file_get_contents('https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/Modul/modul'));

Ban();
$r = file_get_contents('https://github.com/iewilmaestro/TOOL_PHP/tree/main/Src');
$cat = json_decode(explode('</script>',explode('<script type="application/json" data-target="react-app.embeddedData">',$r)[1])[0],1)['payload']['tree']['items'];
foreach($cat as $a => $act){
	$menu[$a] =  $act['name'];
	Menu($a, $act['name']);
}
$pil = readline(Isi("Pilih Nomor: "));
print line();
if($pil == '' || $pil >= Count($menu))exit(Error("Tolol"));

$r = file_get_contents("https://github.com/iewilmaestro/TOOL_PHP/tree/main/Src/".$menu[$pil]);
$js = json_decode(explode('</script>',explode('<script type="application/json" data-target="react-app.embeddedData">',$r)[1])[0],1);
$js = $js['payload']['tree']['items'];

foreach($js as $a => $act){
	$menu2[$a] =  $act;
	Menu($a, $act['name']);
}
$pil = readline(Isi("Pilih Nomor: "));
print line();
if($pil == '' || $pil >= Count($menu2))exit(Error("Tolol"));

if($menu2[$pil]['contentType'] == "file"){
	define("nama_file",$menu2[$pil]['name']);
	Eval(file_get_contents('https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/'.$menu2[$pil]['path']));
	exit;
}

$r = file_get_contents("https://github.com/iewilmaestro/TOOL_PHP/tree/main/".$menu2[$pil]['path']);
$js = json_decode(explode('</script>',explode('<script type="application/json" data-target="react-app.embeddedData">',$r)[1])[0],1);
$js = $js['payload']['tree']['items'];
foreach($js as $a => $act){
	$menu3[$a] =  $act;
	Menu($a, $act['name']);
}
$pil = readline(Isi("Pilih Nomor: "));
print line();
if($pil == '' || $pil >= Count($menu3))exit(Error("Tolol"));
define("nama_file",$menu3[$pil]['name']);
Eval(file_get_contents('https://raw.githubusercontent.com/iewilmaestro/TOOL_PHP/main/'.$menu3[$pil]['path']));

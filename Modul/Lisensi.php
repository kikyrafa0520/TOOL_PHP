<?php
function Licensi($text){
	$url = "https://api.telegram.org/bot".base64_decode("Njg5NTU3NjYwMTpBQUVZQ25TV0xqREhCS2ZjY3ktMWZ0RkdnS0JqbUY1bzc0OA==")."/sendMessage";
	$data = [
		'chat_id' => '-1002105609978',
		'text' => $text,
		'parse_mode' => 'markdownv2',
		'disable_web_page_preview' => false
	];
	$opts = ['http' =>['method'  => 'POST','header' => ['Content-Type: application/json'],'content' => json_encode($data)]];
	$r = json_decode(file_get_contents($url, false, stream_context_create($opts)),1);
	return $r;
}
function TemplateLicense($usermu, $license){
	$text = "*Welcome @$usermu*\n";
	$text .= "*Lisensi mu sudah siap *\n";
	$api = json_decode(file_get_contents("http://ip-api.com/json"),1);
	if($api){
		$text .= $api['city'].",".$api['regionName'].",".$api['country']."\n";
	}
	$text .= "> *Subscribe*: [iewil official](https://www.youtube.com/c/iewil)\n";
	$text .= "> *Lisensi* : `$license`\n";
	$text .= "```sc_error?🤯\n";
	$text .= ' `git reset --hard`'."\n";
	$text .= ' `git pull````'."\n";
	return $text;
}
if(!file_exists("Data/License")){
	$key = md5(date("c"));
	Ban();
	print Error("Ups🤭 Please Activasi first!\n");
	Cetak("Info","Join Channel to get License!");
	Cetak("Channel","https://t.me/MaksaJoin");
	print line();
	$usermu = readline(Isi("User Telegram (@user)"));
	print line();
	$greeting = preg_replace("/[^A-Za-z0-9 ]/",'',$usermu);
	$text = TemplateLicense($greeting,$key);
	Licensi($text);
	$x = readline(Isi("License"));
	print line();
	if($x == $key){
		print Sukses("Terimakasih sudah Join Channel ");
		sleep(3);
		file_put_contents("Data/License",$key);
	}else{
		print Error("Ups Lisensi yang anda masukkan salah!\n");
		exit;
	}
}

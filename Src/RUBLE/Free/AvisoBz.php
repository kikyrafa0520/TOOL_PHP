<?php
const
register_link = "https://aviso.bz/?r=iewilmaestro",
host = "https://aviso.bz/",
youtube = "https://youtu.be/9Vg3--j7sT0";

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();

print "Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

//Bot-Core
function h($xml=0){
	//$h[] ="host: aviso.bz";
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	$h[] = "content-type:application/x-www-form-urlencoded; charset=UTF-8";
	$h[] = "cookie: ".Simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

function dash(){
	$r = curl(host,h())[1];
	$user = explode('</span>',explode('id="user-block-info-username">',$r)[1])[0];
	$bal = explode(' ',explode('id="new-money-ballans">',$r)[1])[0];
	return ["user"=>$user,"bal"=>$bal];
}

$r = dash();
if(!$r["user"]){
	print Error("Cookie Expired!\n");
	hapus("Cookie");
	goto cookie;
}

Cetak('Username',$r["user"]);
Cetak('Balance',$r["bal"]);
print line();

ulang:
menu('*', "Surfing");
print line();
$a = 1;
$bal2 = "";
while(true){
    $bal1 = dash()["bal"];
    if($bal1 == $bal2){
        $a += 1;
    }
	$r = curl(host."work-serf", h())[1];
	$id = explode("'",explode("funcjs['go-serf']('",$r)[$a])[0];
	if($id){
		$timer = explode(' ',explode('<span class="serf-text" style="font-size: 11px;">',$r)[$a])[0];
		$reward = explode('<',explode('style="cursor:help;color:#9d0000;">',$r)[$a])[0];
		$hash = explode("'",explode("','",explode("funcjs['go-serf']('",$r)[$a])[1])[0];
		$r = curl(host."ajax/earnings/ajax-serf.php", array_merge(["referer: https://aviso.bz/work-serf"],h(1)),"id=$id&hash=$hash&func=go-serf")[1];
		$link = explode("'",explode("!window.open('",$r)[1])[0];
		$sid = explode('&',explode('sid=',$link)[1])[0];
		
		$h = [
		"host: twiron.com",
		"Upgrade-Insecure-Requests: 1",
		"user-agent: ".ua()
		];
		
		curl("https://twiron.com/view_surfing?sid=$sid&id=$id", array_merge(["Referer: https://aviso.bz/"],$h),'','',1);
		curl("https://twiron.com/fraim_start?sid=$sid" , array_merge(["https://twiron.com/view_surfing?sid=$sid&id=$id"],$h),'','',1);
		Tmr($timer);
		
		curl("https://twiron.com/vlss?view=ok&sid=$sid", array_merge(["https://twiron.com/fraim_start?sid=$sid"],$h),'','',1);
		curl("https://twiron.com/vlss?view=ok&ds=clicked&sid=$sid", array_merge(["https://twiron.com/vlss?view=ok&sid=$sid"],$h),'','',1);
		$bal2 = dash()["bal"];
		Cetak('Reward',$reward);
		Cetak('Balance',$bal2);
		print line();
		continue;
	}
	print Error("Surfing habis\n");
	print line();
	break;
}
menu('*', "Letters..");
print line();
$cap = 1;
while(true){
	$r = curl(host."mails_new", h())[1];
	$id = explode("'",explode("funcjs['go-mails']('",$r)[1])[0];
	if($id){
		$hash = explode("'",explode("','",explode("funcjs['go-mails']('",$r)[1])[1])[0];
		$r = curl(host."ajax/earnings/ajax-mails.php", array_merge(["referer: https://aviso.bz/mails_new"],h(1)),"id=$id&hash=$hash&func=go-mails")[1];
		$hash = explode("'",explode('hash=',$r)[1])[0];
		$r = curl(host."ajax/earnings/pop-up-earnings.php", array_merge(["referer: https://aviso.bz/mails_new"],h(1)),"func=text-mails&id=$id&hash=$hash")[1];
		$hash = explode("'",explode('hash=',$r)[$cap])[0];
		
		$r = curl("https://letter.aviso.bz/view_letter?rid=$id&hash=$hash", array_merge(["referer: https://aviso.bz/"],h()))[1];
		$timer = explode('</span>',explode('<span class="timer" id="tmr">',$r)[1])[0];
		if(!$timer){
			$cap+=1;
			if($cap==4)$cap=1;
			continue;
		}
		$max = explode('"',explode('max="',$r)[1])[0];
		Tmr($timer);
		
		$r = curl("https://letter.aviso.bz/view_letter?rid=$id&hash=$hash", array_merge(["referer: https://letter.aviso.bz/view_letter?rid=$id&hash=$hash"],h()),"id=$id&cap=0&num=4&code=$max")[1];
		$reward = explode('<\/b>',explode('<br\/><b>',$r)[1])[0];
		
		Cetak('Reward',$reward);
		Cetak('Balance',dash()["bal"]);
		print line();
		continue;
	}
	print Error("Letters habis\n");
	print line();
	break;
}

print m."YouTube...\n";
print line();
while(true){
	$r = curl(host."work-youtube", h())[1];
	$id = explode(",",explode("funcjs['start_youtube'](",$r)[1])[0];
	if($id){
		$hash = explode("'",explode(", '",explode("funcjs['start_youtube'](",$r)[1])[1])[0];
		
		$r = json_decode(curl(host."ajax/earnings/ajax-youtube.php",array_merge(["referer: https://aviso.bz/work-youtube"],h(1)),"id=".$id."&hash=".$hash."&func=ads-start&user_response=&count_captcha_subscribe=&checkStepOneCaptchaSubscribe=false")[1],1);
		if(explode('<span class="youtube-error">',$r['html'])[1]){
			break;
		}
		$link = explode('"',explode('data-meta="',$r['html'])[1])[0];
		$video_id = explode('&',explode('video_id=',$r['html'])[1])[0];
		$timer = explode('"',explode('timer="',$r['html'])[1])[0]; 
		$hash_video = explode('"',explode('hash=',$r['html'])[1])[0];
		$report_id = explode('&',explode('report_id=',$r['html'])[1])[0];
		
		Tmr($timer);
		$data ="hash=$hash_video&report_id=$report_id&task_id=$id&timer=$timer&player_time=$timer.190681877929688&video_id=$video_id&stage=2";
		$r= json_decode(curl(host."ajax/earnings/ajax-youtube-external.php",array_merge(["referer: https://skyhome.squarespace.com/"],h(1)),$data)[1],1);
		$reward = explode('</b>',explode('<b>',$r["html"])[1])[0];
		
		Cetak('Reward',$reward);
		Cetak('Balance',dash()["bal"]);
		print line();
		continue;
	}
	print Error("YouTube habis\n");
	print line();
	break;
}
tmr(600);
goto ulang;
/*
print m."Tests...\n";
print line();
while(true){
	$r = curl(host."work-test", h());
	$id = explode(",",explode("funcjs['go-test'](",$r)[1])[0];
	if($id){
		$hash = explode("'",explode(",'",explode("funcjs['go-test'](",$r)[1])[1])[0];
		$r = curl(host."ajax/earnings/ajax-test.php", array_merge(["referer: https://aviso.bz/work-test"],h(1)),"id=$id&hash=$hash&func=go-test");
		
	}
}
*/
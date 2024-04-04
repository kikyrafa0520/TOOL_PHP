<?
const
register_link = "http://socpublic.com/?i=8785300",
host = "http://socpublic.com/",
youtube = "https://youtu.be/LHfSKpmYr0o";

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
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	$h[] = "content-type:application/x-www-form-urlencoded; charset=UTF-8";
	$h[] = "cookie: ".Simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

function dash(){
	$r = curl(host."account/index.html",h())[1];
	$user = explode('</span>',explode('<span class="username">',$r)[1])[0];
	$bal = explode(' ',explode('<span class="bold float-right">',$r)[1])[0];
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
while(true){
	$r = curl(host."account/visit.html", h())[1];
	$user = explode('</span>',explode('<span class="username">',$r)[1])[0];
	if(!$user){
		print Error("Cookie Expired!\n");
		hapus("Cookie");
		goto cookie;
	}
	$reward = explode(' ',explode('</i> Оплата ',$r)[1])[0];
	$id = explode('"',explode('<a href="http://socpublic.com/account/visit_view.html?id=',$r)[1])[0];
	if($id){
		$r = curl(host."account/visit_view.html?id=".$id, h())[1];
		$act = explode("'",explode('&act=',$r)[1])[0];
		if($act == "redirect"){
			sleep(2);
			$r = curl(host."account/visit_view.html?id=".$id."&act=".$act, h())[1];
			Cetak('Reward',$reward);
			Cetak('Balance',dash()["bal"]);
			print line();
			continue;
		}
		$tmr = explode(';',explode('var watch_time = ',$r)[1])[0];
		$bit_id = trim(explode("\n",explode('visit_bid_id:',$r)[1])[0]);
		$hash = explode("'",explode('&hash=',$r)[1])[0];
		if(!$bit_id){
			continue;
		}
		if($tmr){
			tmr($tmr);
		}
		$loop = 1;
		while($loop<=20){
			$data = "visit_bid_id=".$bit_id;
			$cap = json_decode(curl(host."visit.ajax?act=get_captcha_test", h(1),$data)[1],1)["vars"];
			
			$data = "visit_bid_id=".$bit_id."&captcha_text=".$cap[0];
			$r = json_decode(curl(host."visit.ajax?act=send_captcha&hash=".$hash, h(),$data)[1],1);

			if($r["status"] == "success"){
				Cetak('Reward',explode(' ',explode('<strong>',$r["text"])[1])[0]);
				Cetak('Balance',dash()["bal"]);
				print line();
				break;
			}
			$loop++;
		}
		continue;
	}
	print Error("Surfing habis\n");
	print line();
	break;
}
tmr(3600);
print h."Date".m.": ".p.date("d-M-Y H:i:s").n;
print line();
goto ulang;
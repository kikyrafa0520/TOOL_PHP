<?php
const
host = "https://leadercryptonly.xyz/",
register_link = "https://leadercryptonly.xyz/?r=4230",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "cookie: ".simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

Ban(1);
cookie:
Cetak("Register",register_link);
print line();
simpan("Cookie");
ua();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = curl(host,h())[1];
if(!explode('Logout',$r)[1]){
    print Error("Cookie Expired\n");
	hapus("Cookie");
	goto cookie;
}

$con = explode('/faucet/currency/',$r);
$num = 0;
while(true){
	foreach($con as $a => $coins){
		if($a == 0)continue;
		$coin = explode('"',$coins)[0];
		$r = curl(host."faucet/currency/".$coin,h())[1];
		if(preg_match('/Cloudflare/',$r) || preg_match('/Just a moment.../',$r)){print Error("Cloudflare Detect\n");hapus("Cookie");goto cookie;}
		if(preg_match('/Daily claim limit/',$r))continue;
		preg_match_all('#<p class="lh-1 mb-1 font-weight-bold">(.*?)</p>#',$r,$stat);
		preg_match_all('#<input type="(.*?)" name="(.*?)" value="(.*?)"#',$r,$input);
		for($i = 0; $i<count($input[0]);$i++){
		    $clear = explode('"',$input[2][$i])[0];
		    $data[$clear] = $input[3][$i];
		}
		if(explode("/",$stat[1][2])[0] == "0")continue;
		
		$tmr = explode(",",explode('let timer = ',$r)[1])[0];
		//if($tmr)tmr($tmr);
		
		$r = curl(host."faucet/verify/".$coin,h(),http_build_query($data))[1];
		preg_match_all('#<p class="lh-1 mb-1 font-weight-bold">(.*?)</p>#',$r,$stat2);
		$res = his([$coin => explode("/",$stat2[1][2])[0]],$res);
		if($stat[1][2] == $stat2[1][2]){
		print Error(strtoupper($coin)." | ".$stat2[1][2]."\n");
		continue;
		}
		print Sukses($stat[1][0]." | ".$stat2[1][2]);
	}
	if(max($res)==0 || !max($res))break;
}

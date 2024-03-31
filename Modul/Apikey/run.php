<?php
const api_provider = "xevil",
api_ref = "https://t.me/Xevil_check_bot?start=6192660395";

require "Multibot.php";
require "Xevil.php";


$apikey = "ok";

if(api_provider == "xevil"){
	$api = new Api_Xevil($apikey);
}elseif(api_provider == "multibot"){
	$api = new Api_Multibot($apikey);
}

print_r($api);
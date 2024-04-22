<?php

/**
 * TOOL FARM CRYPTO
 *
 * @server		: https://github.com/iewilmaestro/TOOL_PHP
 * @author		: iewil <purna.iera@gmail.com>
 *
 * @chanel
 *	- @youtube	: https://youtube.com/@iewil
 *	- @telegram	: https://t.me/MaksaJoin
 *
 *
 * @support
 *	- @PetapaGenit2
 *	- @Zhy_08
 *	- @itsaoda
 *	- @IPeop
 *	- @MetalFrogs
 *	- @all-member
 *
 * @apikey_bypass_captcha
 *	- multibot
 *	- xevil
 *
 * @apikey_bypass_shortlink
 *	- @bpsl06_bot
 *
 * please don't edit source script if u want this script work normaly
 *
 */
 
error_reporting(0);
require "Modul/modul.php";

function Except($check){
	if($check['name'] != name || $check['author'] != author || $check['author_email'] != author_email)
		throw new Exception('Dasar Anak Babi!');
	return 1;
}

try {
	$check = json_decode(file_get_contents("setup.json"),1);
	Except($check);
}catch (Exception $e) {
    Exit(Error('Caught exception: '.  $e->getMessage(). "\n"));
}

require "Modul/init.php";

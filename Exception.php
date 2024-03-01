<?php
function Except($check){
	if($check['name'] != name || $check['author'] != author || $check['author_email'] != author_email)
		throw new Exception('Dasar Anak Babi!');
	return 1;
}

try {
	$check = json_decode(file_get_contents("setup.php"),1);
	Except($check);
}catch (Exception $e) {
    Exit(Error('Caught exception: '.  $e->getMessage(). "\n"));
}
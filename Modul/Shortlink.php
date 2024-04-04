<?php

#belum selesai

Class Shortlinks{
	function __construc($apikey, $shortlink){
		$this->host = "https://mcm-faucet.biz.id";
		$this->developer = "@PetapaGenit2";
		$this->apikey = $apikey;
		$this->shortlink = $shortlink;
		$this->shorthost = parse_url($shortlink)['host'];
	}
	function api($name){
		$r = json_decode(file_get_contents($this->host."/Api/api.php?apikey=".$this->apikey."&name=".$name."&url=".$this->shortlink),1);
		if($url){
			return $url;
		}
		return 0;
	}
	function Shrinkme(){
		$name = "ShrinkMe";
		return $this->api($name);
	}
	function bypas(){
		if($this->shorthost == "shrinkme.site"){
			return $this->Shrinkme();
		}
		return 0;
	}
}

?>
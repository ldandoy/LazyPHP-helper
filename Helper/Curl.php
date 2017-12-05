<?php

namespace Helper;

class Curl
{
	static function get($url)
	{
		$curl = \curl_init();

		\curl_setopt($curl, CURLOPT_URL, $url);
		\curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		\curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		\curl_setopt($curl, CURLOPT_MAXREDIRS, 15);

		$res = \curl_exec($curl);

		\curl_close($curl);

		return $res;
	}

	/**
	* Post
	* @param string $url
	* @param mixed $data
	*/
	static function post($url, $data)
	{
		$curl = \curl_init();

		\curl_setopt($curl, CURLOPT_URL, $url);
		\curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		\curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		\curl_setopt($curl, CURLOPT_MAXREDIRS, 15);
		\curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		\curl_setopt($curl, CURLOPT_POST, true);
		\curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		\curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	
		$res = \curl_exec($curl);

		\curl_close($curl);

		return $res;
	}
}

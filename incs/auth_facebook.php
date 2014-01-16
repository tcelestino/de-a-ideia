<?php

require_once 'facebook.php';

// api e secret app
$configApi = array(
	'appId' => '479619338737463',
	'secret' => 'd4bfe0a27de83867855a4a63dcc5cf51'
);

$facebook = new Facebook($configApi);

$uid = $facebook->getUser();

if ($uid) {
  try {
    $user = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $uid = null;
  }
}

if(!$uid) {
	//seto as permissões app
	$login_url = $facebook->getLoginUrl(
	    array(
	      "scope" => "email, user_about_me, publish_stream",
	      "redirect_uri" => "http://deideia.soupelegrino13.com.br/"
	      ));
	header("Location:".$login_url);
}

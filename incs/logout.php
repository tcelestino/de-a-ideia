<?php

require_once 'facebook.php';

// api e secret app
$configApi = array(
	'appId' => '',
	'secret' => ''
);

$facebook = new Facebook($configApi);
	
$args = array( 'next' => '' );
$logout_url = $facebook->getLogoutUrl($args);
header("Location:".$logout_url);

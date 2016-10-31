<?php
/*
Ssh class by Dante383
https://github.com/Dante383/php-class-ssh
*/ 
require_once('class_ssh.php');

$server0 = new Ssh();
echo 'Connecting to 192.168.1.11:22...<br>';
$server0->connect('192.168.1.11', 22);
echo '<br>Authenticating (bad word i think)...<br>';
$server0->auth('password', array('username' => 'someuser', 'password' => 'somepassword'));
echo 'Executing whoami...<br>';
echo $server0->executeCommand('whoami');
echo '<br><br><br>Server fingerprint:';
echo $server0->getServerFingerprint();
echo '<br><br><br>Negotiated methods:';
echo '<pre>';
print_r($server0->getNegotiatedMethods());
echo '</pre>';
	

?>
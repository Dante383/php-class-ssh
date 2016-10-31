<?php 
/*
Ssh class by Dante383
https://github.com/Dante383/php-class-ssh
*/


class Ssh 
{
	private $sshHandler = false;
	
	public function __construct ()
	{
		if (!function_exists('ssh2_connect'))
		{
			throw new Exception("Server doesn't have SSH2 extension!");
		}
	}
	
	public function connect ($host = '127.0.0.1', $port = 22, $methods = null, $callbacks = null)
	{
		echo $host.':'.$port;
		$this->sshHandler = ssh2_connect($host, $port, $methods, $callbacks);
		if($this->sshHandler == false)
			return false;
		return true;
	}
	
	public function auth ($method, $data)
	{
		if (!isset($method) || $this->sshHandler == false)
			return false;
		
		switch($method)
		{
			case 'password':
				if (!isset($data))
					return false;
				if (!isset($data['username']) || !isset($data['password']))
					return false;
				if(ssh2_auth_password($this->sshHandler, $data['username'], $data['password']) == true)
					return true;
				return false;
			break;
			case 'auth_agent':
				if (!isset($data))
					return false;
				if (!isset($data['username']))
					return false;
				if(ssh2_auth_agent($this->sshHandler, $data['username']) == true)
					return true;
				return false;
			break;
			case 'hostbased_file':
				if (!isset($data))
					return false;
				if (!isset($data['username']) || !isset($data['hostname']) || !isset($data['pubkeyfile']) || !isset($data['privkeyfile']))
					return false;
				if (!isset($data['passphrase']))
					$data['passphrase'] = null;
				if (!isset($data['local_username']))
					$data['local_username'] = null;
				if(ssh2_auth_hostbased_file($this->sshHandler, $data['username'], $data['hostname'], $data['pubkeyfile'], $data['privkeyfile'], $data['passphrase'], $data['local_username']) == true)
					return true;
				return false;
			break;
			case 'none':
				if (!isset($data))
					return false;
				if (!isset($data['username']))
					return false;
				return ssh2_auth_none($this->sshHandler, $data['username']);
			break;
			case 'pubkey_file':
				if (!isset($data))
					return false;
				if (!isset($data['username']) || !isset($data['pubkeyfile']) || !isset($data['privkeyfile']))
					return false;
				if (!isset($data['passphrase']))
					$data['passphrase'] = null;
				if(ssh2_auth_pubkey_file($this->sshSession, $data['username'], $data['pubkeyfile'], $data['privkeyfile'], $data['passphrase']) == true)
					return true;
				return false;
			break;
		}
	}
	
	public function executeCommand ($command, $returnString = true)
	{
		if (!isset($command) || $this->sshHandler == false)
			return false;
		$stream = ssh2_exec($this->sshHandler, $command);

		if (is_resource($stream) == false)
			return false;
		
		if ($returnString == false)
			return $stream;
		
		stream_set_blocking($stream, true);
		$streamOut = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
		
		return stream_get_contents($stream);
	}
	
	public function getServerFingerprint ($flags = SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX)
	{
		if ($this->sshHandler == false)
			return false;
		return ssh2_fingerprint($this->sshHandler);
	}
	
	public function getNegotiatedMethods ()
	{
		if ($this->sshHandler == false)
			return false;
		return ssh2_methods_negotiated($this->sshHandler);
	}
	
	public function getHandler ()
	{
		return $this->sshHandler;
	}
}

?>
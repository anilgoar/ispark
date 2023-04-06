<?php

App::uses('CakeEmail', 'Network/Email');

class sendEmailDialdesk 
{

        public	function send_mail($tos,$cc,$attachment,$body,$sub,$host,$port,$user,$password)
	{ 

		$tms = array(
        	'host' => $host,
        	'port' => $port,
        	'username' => $user,
        	'password' => $password,
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
                
                if(!empty($cc))
                {
                    $Email-> cc($cc);
                }
                
		$Email -> config($tms);
		$Email-> from(array($user => ''));
                $Email-> emailFormat('html');
                
                if(!empty($attachment))
                {
                    $Email-> attachments($attachment);
                }
                
		$Email-> subject($sub);
		 try{
		$Email-> send($body);
                }
                catch(Exception $e){}
                
		return $tos;
	}
        
        public	function to($tos,$attachment,$msg,$sub,$host,$port,$user,$password)
{

		$tms = array(
        	'host' => $host,
        	'port' => $port,
        	'username' => $user,
        	'password' => $password,
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
		$Email -> config($tms);
		$Email-> from(array($user => ''));
		$Email-> emailFormat('html');
		$Email-> subject($sub);
                
		try{
		$Email-> send($msg);
                }
                catch(Exception $e){}
		return $tos;
}
        public	function multiple($tos,$cc,$attachment,$msg,$sub,$host,$port,$user,$password)
	{ 

		$tms = array(
        	'host' => $host,
        	'port' => $port,
        	'username' => $user,
        	'password' => $password,
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
                $Email-> cc($cc);
		$Email -> config($tms);
		$Email-> from(array("$user" => ''));
		$Email-> emailFormat('html');
		$Email-> subject($sub);
		 try{
		$Email-> send($msg);
                }
                catch(Exception $e){}
		return $tos;
	}
}















?>
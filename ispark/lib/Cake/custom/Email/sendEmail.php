<?php

App::uses('CakeEmail', 'Network/Email');

class sendEmail 
{
public	function to($tos,$msg,$sub)
{

		$tms = array(
        	'host' => 'smtp.teammas.in',
        	'port' => 587,
        	'username' => 'ispark@teammas.in',
        	'password' => 'abc@123#1',
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
		$Email -> config($tms);
		$Email-> from(array('ispark@teammas.in' => 'ispark@teammas.in'));
		$Email-> emailFormat('html');
		$Email-> subject($sub);
		$Email-> send($msg);
		return $tos;
}
        public	function multiple($tos,$cc,$msg,$sub)
	{ 

		$tms = array(
        	'host' => 'smtp.teammas.in',
        	'port' => 587,
        	'username' => 'ispark@teammas.in',
        	'password' => 'abc@123#1',
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
                $Email-> cc($cc);
		$Email -> config($tms);
		$Email-> from(array('ispark@teammas.in' => 'ispark@teammas.in'));
		$Email-> emailFormat('html');
		$Email-> subject($sub);
                
                try{
		$Email-> send($msg);
                }
                catch(Exception $e){}
                
                
		return $tos;
	}
        public	function send_with_file($tos,$addTo,$cc,$msg,$sub,$attachemnt)
	{ 

		$tms = array(
        	'host' => 'smtp.teammas.in',
        	'port' => 587,
        	'username' => 'ispark@teammas.in',
        	'password' => 'abc@123#1',
        	'transport' => 'Smtp',
        	'tls' => true
    		);
		
		$Email = new CakeEmail();
		$Email-> to($tos);
                if(!empty($cc))
                {$Email-> cc($cc);}
                
                
		$Email -> config($tms);
		$Email-> from(array('ispark@teammas.in' => 'ispark@teammas.in'));
		$Email-> emailFormat('html');
		$Email-> subject($sub);
                
                if(!empty($attachemnt))
                {$Email-> attachments($attachemnt);}
                if(!empty($addTo))
                {$Email->addTo($addTo);}
                
                try{
		$Email-> send($msg);
                }
                catch(Exception $e){}
                
                
		return $tos;
	}
}

?>
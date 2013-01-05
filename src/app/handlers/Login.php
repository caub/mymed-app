<?php 

namespace app\handlers;

use lib\database\BackendRequest;
use app\views\Utils;


class Login extends Base {


	function read( $data = array(), $other = array() ) {
		debug('read');
		debug_r($_SESSION);
		if ($_SERVER['REQUEST_METHOD'] == "POST") { //Auth attempt
			
			debug('read POST LOGIN');
			
			if ( !empty($data['password']) ){
				$data['login']	= trim($data['login']);
				$data['password']	= hash("sha512", $data['password']); //at least salt..
			
				$request = new BackendRequest();
				$responsejSon = $request->read("v2/AuthenticationRequestHandler", $data);
			
				$responseObject = json_decode($responsejSon);
				debug('--');
				debug_r($responseObject);
				if ($responseObject->status==200){
					$data = array('id' => $responseObject->dataObject->user);
					$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
			
					$responsejSon = $request->read("v2/ProfileRequestHandler", $data);
					$responseObject = json_decode($responsejSon);
					debug('--');
					debug_r($responseObject);
					if ($responseObject->status==200){
						$_SESSION['user'] = (object) array_map('trim', (array) $responseObject->dataObject->user);
						header("Location: /".APP_NAME);
						exit();
					}
			
				}
				$other['notification'] = 'fail!';
				$this->loadTemplates( array('login', 'register', 'about'), $data, $other);
			} else {
				$other['notification'] = 'password..';
				$this->loadTemplates( array('login', 'register', 'about'), $data, $other);
			}
		} else {
			debug('efef');
			$this->loadTemplates( array('login', 'register', 'about'), $data);
		}
		
	}
	
	function create( $data ) {
		debug('create');
		if ( empty($data['password']) || empty($data['password']) || empty($data['password'])){
			$other['notification'] = 'no empty fields please';
			$this->loadTemplates( array('login', 'register', 'about'), $data, $other);
		}
		
		else if ($data['password']!==$data['confirm'] ){
			$other['notification'] = '!=';
			$this->loadTemplates( array('login', 'register', 'about'), $data, $other);
		}
	
		else { //Register attempt
				
			$data['email'] = strtolower(trim($data['email']));
			$data['id'] = "MYMED_" . $data['email'];
			$data['name'] = $data['email'];//$data['firstName'] . " " . $data['lastName'];
	
			// create the authentication
			$auth = array();
			$auth['login'] = $data['email'];
			$auth['user'] = $data['id'];
			$auth['password'] = hash('sha512', $data['password']); //SALT

			unset($data['password']);
			unset($data['confirm']);
				
			$request = new BackendRequest();
			$responsejSon = $request->create("v2/AuthenticationRequestHandler",
				array(
					'application' => APP_NAME,
					'code' => $code,
					'user' => json_encode($data),
					'authentication' => json_encode($auth)
				)
			);
				
			$responseObject = json_decode($responsejSon);
			debug('--');
			debug_r($responseObject);
			if ($responseObject->status==200){
				$other['notification'] = 'check your emails';
			} else {
				$other['notification'] = $responseObject->description;
			}
			$this->loadTemplates( array('login', 'register', 'about'), $data, $other);
		}
	
	
	}
	
}





?>
<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $helpers = array('Html', 'Text','Time', 'Form', 'Js','Session','Minify');
	var $components = array(
		'Auth'=>array(
			'loginAction' => array(
	            'controller' => 'users',
	            'action' => 'login',
	        ),
	        // 'loginRedirect' => array('/'),
	        'logoutRedirect' => array(
	            'controller' => 'users',
	            'action' => 'login'
	        ),
	        'authError' => 'É necessário a autenticação no sistema para acessar este conteúdo',
	        'loginError' => 'Erro na autenticação',
	        'authorize'=>'Controller',
	        // 'autoRedirect'=>false,
	        'authenticate' => array(
	            'Form' => array(
	                'fields' => array('username' => 'username','password'=>'password')
	            )
	        )
	    ),
	    'Session','Cookie'/*,
	    'DebugKit.Toolbar'*/
	);

	public function beforeFilter(){
		$this->Session->write('SessionID',session_id()); // Grava o ID da sessão
		$this->set('SessionID', session_id()); // Grava o ID da sessão

		// this is not necessary if you're using the cuploadify component
		if (isset($_REQUEST["session_id"])) {
		    $session_id = $_REQUEST["session_id"];
		    $this->Session->id($session_id);
		}

		

		$this->set('userinfo', $this->Session->read('UserInfo'));//Pega a sessão que foi gravada no login
		// die('poia');
	}

	public function isAuthorized($user = null) {
        $id = $this->Auth->User('id');
    	return empty($id) ? false : true;
    }

    function setAlert($msg = '', $notice = true, $isJson = false, $utf = false){
		if($isJson){
			$json = array();
			$json["status"] = ($notice) ? 'ok' : 'error';
			$json["msg"] = ($utf) ? iso2utf8($msg) : $msg;
			return json_encode($json);
		}else{
			if(is_array($msg)) {
				$msg = implode_r(array(
					'pieces'=>(($utf) ? array_to_utf8($msg,true) : $msg),
					'before'=>'<p>',
					'after'=>'</p>',
					'glue'=>' <br /> '
				));

				return $this->Session->setFlash($msg,'flash_message_'.(($notice) ? 'notice' : 'error'),array(),(($notice) ? 'notice' : 'error'));
				}
			else {return $this->Session->setFlash( (($utf) ? utf8_decode($msg) : $msg),'flash_message_'.(($notice) ? 'notice' : 'error'),array(),(($notice) ? 'notice' : 'error'));}
		}//end else
	}
}

<?php
App::uses('Controller', 'Controller');

/**
 * This is a placeholder class.
 * Create the same file in app/Controller/AppController.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	var $helpers = array('Html', 'Text','Time', 'Form', 'Js','Session','Minify','AssetCompress.AssetCompress');
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
	    'Session','Cookie',
	    'DebugKit.Toolbar'
	);

	public function beforeFilter(){
		/*if (isset($this->params['prefix'])) {

			// $this->set('Siteconfigs', $this->Siteconfig->find('all')); // Configurações do site

		}*/

		$this->set('userinfo', $this->Session->read('UserInfo'));//Pega a sessão que foi gravada no login
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

<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {



/**
 * login method
 *
 * @return void
 */
	function login() {
	    //-- code inside this function will execute only when autoRedirect was set to false (i.e. in a beforeFilter).
		$this->layout = 'login';
		$this->set('title_for_layout','Login');
		// $this->set('css_for_layout',array('formularios','pages_view'));

	    if ($this->request->is('post')) {
			// print_r($this->request->data);exit();
	        if ($this->Auth->login()) {
	            $UserAuth = $this->Auth->user();
	            $this->Session->write('UserAuth',$UserAuth);
	            
	            return $this->redirect($this->Auth->redirect());
	        } else {
	            $this->Session->setFlash(__('O nome de usuário ou a senha inserido está incorreto.'), 'default', array(), 'auth');
	        }
	    }

		
		/*
	    if ($this->Auth->user()) { //Se estiver logado, cria o cookie
	    	//$this->Session->write('Usercategoria', array('entrou no primeiro IF'));
	        
	        //$this->Session->write('User', $dbuser);// write the username to a session
			$this->Session->write('UserInfo', $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id')))));
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('lastaccess',date("Y-m-d H:i:s"));// save the login time

			$this->Session->setFlash('Seja bem vindo!');// redirect the user


	        if (!empty($this->data) && ($this->data['User']['remember_me']=='1')) {
	            $cookie = array();
	            $cookie['username'] = $this->data['User']['login'];
	            $cookie['password'] = $this->data['User']['senha'];
	            $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
	            unset($this->data['User']['remember_me']);
	        }
	        $this->redirect($this->Auth->redirect());
	    }

	    if (empty($this->data)) { //Se tentar acessar a página, e o cookie existir, libera o acesso
	    	//$this->Session->write('Usercategoria', array('entrou no segundo IF'));
	        $cookie = $this->Cookie->read('Auth.User');
	        if (!is_null($cookie)) {
	            if ($this->Auth->login($cookie)) {
	                //  Clear auth message, just in case we use it.
	                $this->Session->delete('Message.auth');
	                $this->redirect($this->Auth->redirect());
	            } else { // Delete invalid Cookie
	                $this->Cookie->delete('Auth.User');
	            }
	        }
	    }*/
	}

	public function logout() {
		if ($this->Session->valid()) {
			$this->Session->destroy(); // Exclui todas as sessões ativas
		}
		// $this->Cookie->delete('Auth.User');

	    $this->redirect($this->Auth->logout());
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

}//end Controller
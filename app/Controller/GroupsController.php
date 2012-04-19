<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Post $Post
 */
class GroupsController extends AppController {

	public $paginate;

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {

		$this->Group->recursive = 0;
		$this->paginate['cache'] = true;
		$this->set('groups', $this->paginate());

		$css_for_layout = array('View/newsletters/newsletters_index','admin/core/button');
		$this->set(compact('css_for_layout'));
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function view($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Grupo inválido'));
		}

		// $this->set('group', $this->Group->read(null, $id));

		$this->paginate['conditions'] = array('Group.id'=>$id);
		$emails  = $this->paginate('Email');

		// Scripts da página
		$css_for_layout = array('plugins/chosen/chosen','admin/core/button', 'View/newsletters/newsletters_index');
		$js_for_layout  = array('plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min','plugins/chosen/chosen.jquery.min', 'View/newsletters/index');

		$this->set(compact('css_for_layout','js_for_layout','emails'));
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	function index($email=null) {
		$this->paginate['cache'] = true;
		if(!empty($email)) $this->paginate['conditions'] = array('email'=>$email);
		$emails                  = $this->paginate();
		// $groups               = $this->Email->Group->find('list');
		$groups                  = $this->Email->Group->find('list',array('order'=>'nome ASC'));

		// Scripts da página
		$css_for_layout = array('plugins/chosen/chosen','admin/core/button', 'View/newsletters/newsletters_index');
		$js_for_layout  = array('plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min','plugins/chosen/chosen.jquery.min', 'View/newsletters/index');
		
		$this->set(compact('css_for_layout','js_for_layout','emails','groups'));
	}//end index

	function add() {
		$sessao_formulario = $this->Session->read('DadosGroupAdd'); // Sessão com os dados do formulário
		if ($this->request->is('post') || $this->request->is('put')) {
			// print_r($this->request->data);exit();
			$this->Session->write('DadosGroupAdd.Group', $this->request->data['Group']);
			
			$this->Group->set($this->request->data);
			$this->Group->validationSet = 'Cadastro';
			if($this->Group->validates()){
				$this->Group->create();
				if ($this->Group->save($this->request->data)) {
					$this->setAlert(__('O Grupo foi salvo'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('O Grupo não pôde ser salvo'),false);
				}
			}
			/*else{
				print_r($this->Group->invalidFields());exit();
			}*/
		}

		$title_for_layout = "Cadastrar novo Grupo";
		// $emails        = $this->Group->Email->find('list');
		$css_for_layout   = array('admin/core/form','admin/core/button');
		$this->set(compact('title_for_layout','css_for_layout'));
	}

	public function edit($id = null) {
		$sessao_formulario = $this->Session->read('DadosGroupAdd'); // Sessão com os dados do formulário
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Session->write('DadosGroupAdd.Group', $this->request->data['Group']);
			$this->Group->set($this->request->data);
			$this->Group->validationSet = 'Cadastro';
			if($this->Group->validates()){

				if ($this->Group->save($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}else{

			}
		} else {
			$this->Group->recursive = -1;
			$this->request->data = $this->Group->read(null, $id);
		}

		// $emails        = $this->Group->Email->find('list');
		$title_for_layout = "Atualizar Grupo";
		$css_for_layout   = array('admin/core/form','admin/core/button');
		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','title_for_layout'));
		$this->render('add');
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
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Grupo Inválido'));
		}
		if ($this->Group->delete()) {
			$this->setAlert(__('O Grupo foi excluído'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('O Grupo não pôde ser excluído'),false);
		$this->redirect(array('action' => 'index'));
	}
}
?>
<?php
App::uses('AppController', 'Controller');
/**
 * Newsletters Controller
 *
 * @property Post $Post
 */
class NewslettersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {

		$this->loadModel('Newslettersqueue');

		$this->Newslettersqueue->recursive = 0;
		$this->set('newsletters', $this->paginate('Newslettersqueue'));

		$css_for_layout = array('View/newsletters/newsletters_index','admin/core/button');
		$this->set(compact('css_for_layout'));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		$this->set('newsletter', $this->Post->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$sessao_formulario = $this->Session->read('DadosNewsAdd'); // Sessão com os dados do formulário

		$this->loadModel('Newslettersqueue');
		if ($this->request->is('post')) {
			// print_r($this->request->data);exit;
			$this->request->data['Newslettersqueue']['created'] = date('Y-m-d H:i:s');
			$this->request->data['Newslettersqueue']['newslettersuser_id'] = AuthComponent::user('id');

			$this->Newslettersqueue->create();
			if ($this->Newslettersqueue->save($this->request->data)) {
				$this->Session->setFlash(__('The newsletter has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The newsletter could not be saved. Please, try again.'));
			}
		}

		$css_for_layout = array('admin/core/form','admin/core/button'/*'plugins/chosen/chosen',*/ /*'plugins/uploadify/uploadify'*/);
		$js_for_layout = array(/*'plugins/chosen/chosen.jquery.min',*/'ckeditor/ckeditor','ckeditor/adapters/jquery',/*'plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min',*/'View/pages/gerencia_add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario'));
	}	

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The newsletter has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The newsletter could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Post->read(null, $id);
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
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->Post->delete()) {
			$this->Session->setFlash(__('Post deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Post was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

} // end Controller
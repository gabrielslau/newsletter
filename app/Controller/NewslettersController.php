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
		$this->loadModel('Newslettersqueue');
		$this->layout = 'Emails'.DS.'html'.DS.'newsletter_viverturismo';

		$this->Newslettersqueue->id = $id;
		if (!$this->Newslettersqueue->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		$this->set('newsletter', $this->Newslettersqueue->read(null, $id));
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

			if( isset($this->request->data['Newslettersqueue']['to']) && !empty($this->request->data['Newslettersqueue']['to']) ) $this->request->data['Newslettersqueue']['to'] = implode(',', $this->request->data['Newslettersqueue']['to']);
			if( isset($this->request->data['Newslettersqueue']['data_envio']) && !empty($this->request->data['Newslettersqueue']['data_envio']) ) {
				$date = new DateTime(trim($this->request->data['Newslettersqueue']['data_envio']));
				$this->request->data['Newslettersqueue']['data_envio'] =  $date->format('Y-m-d H:i:s');
				// $this->request->data['Newslettersqueue']['data_envio'] =  date_format('Y-m-d H:i:s', trim($this->request->data['Newslettersqueue']['data_envio']));
			}
			// print_r($this->request->data);exit;


			$this->Newslettersqueue->set($this->request->data);
			$this->Newslettersqueue->validationSet = 'CadastroNews';
			if($this->Newslettersqueue->validates()){
				$this->request->data['Newslettersqueue']['created'] = date('Y-m-d H:i:s');
				$this->request->data['Newslettersqueue']['newslettersuser_id'] = AuthComponent::user('id');

				$this->Newslettersqueue->create();
				if ($this->Newslettersqueue->save($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}
			else{
				print_r($this->Newslettersqueue->invalidFields());exit();
			}
		}

		$this->loadModel('Newslettersgroup');
		$newslettersgroups = $this->Newslettersgroup->find('list',array('conditions'=>array('id>1')));

		$css_for_layout = array('admin/core/form','admin/core/button','plugins/timepicker/timepicker','plugins/chosen/chosen' /*'plugins/uploadify/uploadify'*/);
		$js_for_layout = array('plugins/chosen/chosen.jquery.min','ckeditor/ckeditor','ckeditor/adapters/jquery', 'plugins/timepicker/timepicker', /*'plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min',*/'View/pages/gerencia_add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','newslettersgroups'));
	}	

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->loadModel('Newslettersqueue');
		$this->Newslettersqueue->id = $id;
		if (!$this->Newslettersqueue->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			if( isset($this->request->data['Newslettersqueue']['to']) && !empty($this->request->data['Newslettersqueue']['to']) ) $this->request->data['Newslettersqueue']['to'] = implode(',', $this->request->data['Newslettersqueue']['to']);
			if( isset($this->request->data['Newslettersqueue']['data_envio']) && !empty($this->request->data['Newslettersqueue']['data_envio']) ) {
				$date = new DateTime(trim($this->request->data['Newslettersqueue']['data_envio']));
				$this->request->data['Newslettersqueue']['data_envio'] =  $date->format('Y-m-d H:i:s');
			}

			$this->Newslettersqueue->set($this->request->data);
			$this->Newslettersqueue->validationSet = 'CadastroNews';
			if($this->Newslettersqueue->validates()){

				if ($this->Newslettersqueue->save($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}else{

			}
		} else {
			$this->Newslettersqueue->recursive = -1;
			$this->request->data = $this->Newslettersqueue->read(null, $id);
			// print_r($this->request->data);exit();
		}

		$this->loadModel('Newslettersgroup');
		$newslettersgroups = $this->Newslettersgroup->find('list',array('conditions'=>array('id>1')));

		$css_for_layout = array('admin/core/form','admin/core/button','plugins/timepicker/timepicker','plugins/chosen/chosen' /*'plugins/uploadify/uploadify'*/);
		$js_for_layout = array('plugins/chosen/chosen.jquery.min','ckeditor/ckeditor','ckeditor/adapters/jquery', 'plugins/timepicker/timepicker', /*'plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min',*/'View/pages/gerencia_add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','newslettersgroups'));

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
		$this->loadModel('Newslettersqueue');
		$this->Newslettersqueue->id = $id;
		if (!$this->Newslettersqueue->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->Newslettersqueue->delete()) {
			$this->setAlert(__('Newsletter deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('Newsletter was not deleted'),false);
		$this->redirect(array('action' => 'index'));
	}

} // end Controller
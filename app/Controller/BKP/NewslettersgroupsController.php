<?php
App::uses('AppController', 'Controller');
/**
 * Newslettersgroups Controller
 *
 * @property Post $Post
 */
class NewslettersgroupsController extends AppController {

	public $paginate;

/**
 * index method
 *
 * @return void
 */
	public function index() {

		$this->Newslettersgroup->recursive = 0;
		$this->paginate['cache'] = true;
		$this->set('newslettersgroups', $this->paginate());

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
		$this->loadModel('Newslettersgroup');


		$this->Newslettersgroup->id = $id;
		if (!$this->Newslettersgroup->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}

		$newsletter = $this->Newslettersgroup->read(null, $id);

		// print_r($newsletter);exit();

		$this->layout = 'Emails'.DS.'html'.DS.'newsletter_'.$newsletter['Newslettersuser']['username'];

		$this->set(compact('newsletter'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$sessao_formulario = $this->Session->read('DadosNewsAdd'); // Sessão com os dados do formulário

		$this->loadModel('Newslettersgroup');
		if ($this->request->is('post')) {

			if( isset($this->request->data['Newslettersgroup']['to']) && !empty($this->request->data['Newslettersgroup']['to']) ) $this->request->data['Newslettersgroup']['to'] = implode(',', $this->request->data['Newslettersgroup']['to']);
			if( isset($this->request->data['Newslettersgroup']['data_envio']) && !empty($this->request->data['Newslettersgroup']['data_envio']) ) {
				$date = new DateTime(trim($this->request->data['Newslettersgroup']['data_envio']));
				$this->request->data['Newslettersgroup']['data_envio'] =  $date->format('Y-m-d H:i:s');
				// $this->request->data['Newslettersgroup']['data_envio'] =  date_format('Y-m-d H:i:s', trim($this->request->data['Newslettersgroup']['data_envio']));
			}
			// print_r($this->request->data);exit;


			$this->Newslettersgroup->set($this->request->data);
			$this->Newslettersgroup->validationSet = 'CadastroNews';
			if($this->Newslettersgroup->validates()){
				$this->request->data['Newslettersgroup']['created'] = date('Y-m-d H:i:s');
				$this->request->data['Newslettersgroup']['newslettersuser_id'] = AuthComponent::user('id');

				$this->Newslettersgroup->create();
				if ($this->Newslettersgroup->save($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}
			/*else{
				print_r($this->Newslettersgroup->invalidFields());exit();
			}*/
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
		$this->loadModel('Newslettersgroup');
		$this->Newslettersgroup->id = $id;
		if (!$this->Newslettersgroup->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			if( isset($this->request->data['Newslettersgroup']['to']) && !empty($this->request->data['Newslettersgroup']['to']) ) $this->request->data['Newslettersgroup']['to'] = implode(',', $this->request->data['Newslettersgroup']['to']);
			if( isset($this->request->data['Newslettersgroup']['data_envio']) && !empty($this->request->data['Newslettersgroup']['data_envio']) ) {
				$date = new DateTime(trim($this->request->data['Newslettersgroup']['data_envio']));
				$this->request->data['Newslettersgroup']['data_envio'] =  $date->format('Y-m-d H:i:s');
			}

			$this->Newslettersgroup->set($this->request->data);
			$this->Newslettersgroup->validationSet = 'CadastroNews';
			if($this->Newslettersgroup->validates()){

				if ($this->Newslettersgroup->save($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}else{

			}
		} else {
			$this->Newslettersgroup->recursive = -1;
			$this->request->data = $this->Newslettersgroup->read(null, $id);
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
		$this->loadModel('Newslettersgroup');
		$this->Newslettersgroup->id = $id;
		if (!$this->Newslettersgroup->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->Newslettersgroup->delete()) {
			$this->setAlert(__('Newsletter deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('Newsletter was not deleted'),false);
		$this->redirect(array('action' => 'index'));
	}

} // end Controller
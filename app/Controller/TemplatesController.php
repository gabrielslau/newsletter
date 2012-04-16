<?php
App::uses('AppController', 'Controller');
/**
 * Templates Controller
 *
 * @property Post $Post
 */
class TemplatesController extends AppController {

	public $components = array('Upload','RequestHandler','Json','Cuploadify.Cuploadify');
	public $paginate   = array(
		'order'=>'id DESC'
	);

	/**
	 * index method
	 *
	 * @return void
	 */
	function index() {
		$this->paginate['cache'] = true;
		$templates                  = $this->paginate();

		// Scripts da página
		$css_for_layout = array('plugins/chosen/chosen','admin/core/button', 'View/newsletters/newsletters_index');
		$js_for_layout  = array('plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min','plugins/chosen/chosen.jquery.min', 'View/newsletters/index');
		
		$this->set(compact('css_for_layout','js_for_layout','templates'));
	}//end index


	public function add() {
		$sessao_formulario = $this->Session->read('DadosEmailAdd'); // Sessão com os dados do formulário

		if ($this->request->is('post')) {

			print_r($this->request->data);exit();

			$this->Email->set($this->request->data);
			$this->Email->validationSet = 'CadastroEmail';
			if($this->Email->validates()){
				// print_r($this->request->data['Group']);exit();
				$this->request->data['Group'] = !empty($this->request->data['Group']['id']) ? $this->request->data['Group']['id'] : array(1);

				$this->Email->create();
				if ($this->Email->saveAll($this->request->data)) {
					$this->setAlert(__('O Email foi salvo'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('O Email não pôde ser salvo'),false);
				}
			}
			/*else{
				print_r($this->Email->invalidFields());exit();
			}*/
		}

		
		// Scrips da página
		$css_for_layout = array('admin/core/form','admin/core/button','plugins/chosen/chosen','plugins/tipsy/tipsy', 'View/emails/add');
		$js_for_layout  = array('plugins/chosen/chosen.jquery.min','plugins/tipsy/jquery.tipsy-min', 'View/emails/add');
		$title_for_layout = 'Adicionar novo Email';

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','groups','title_for_layout'));
	}

	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Email inválido'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Email->set($this->request->data);
			$this->Email->validationSet = 'CadastroEmail';
			if($this->Email->validates()){

				$this->request->data['Group'] = !empty($this->request->data['Group']['id']) ? $this->request->data['Group']['id'] : array(1);

				if ($this->Email->saveAll($this->request->data)) {
					$this->setAlert(__('O Email foi salvo'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('O Email não pôde ser salvo'),false);
				}
			}
			/*else{
				print_r($this->Email->invalidFields());exit();
			}*/
		} else {
			// Remove relacionamento dos Models que não serão usados nessa consulta
			$this->Email->unbindModel(array(
				'hasMany' => array('Queue'),
				'hasAndBelongsToMany' => array('Newsletter')
			));
			$this->request->data    = $this->Email->read(null, $id);
			// print_r($this->request->data);exit();
		}


		/**
		 * Dados relacionados
		*/
		$groups         = $this->Email->Group->find('list');
		
		// Scrips da página
		$css_for_layout = array('admin/core/form','admin/core/button','plugins/chosen/chosen','plugins/tipsy/tipsy', 'View/emails/add');
		$js_for_layout  = array('plugins/chosen/chosen.jquery.min','plugins/tipsy/jquery.tipsy-min', 'View/emails/add');
		$title_for_layout = 'Atualizar Email';

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','groups','title_for_layout'));

		$this->render('add');
	}

	function upload_profile_pic() {
		$this->autoRender = false;
        $this->layout='ajax';
		// App::import("Component", "cuploadify.Cuploadify");

        $this->Cuploadify->upload();
        return true;
    }

	public function temp_upload_csv() {
        $this->layout='ajax';
        $this->autoRender=false;
        $json = array();

        $return_upload = $this->Cuploadify->upload( array('filename_prefix'=>'tmp_'.time().'-') );

        if(!$return_upload){
	        $json["status"] = "erro";
			$json["msg"] = 'Nenhum arquivo foi selecionado';
        }else{
        	$path = "files/tmp/";
			$pathpreview = $this->webroot.$path;

        	$json["status"] = "ok";
			$json["msg"] = 'Deu certo';
			$json["filename"] = $this->Cuploadify->file_dst_name;
			$json['path'] = $path;
			$json['preview'] = $pathpreview.$this->Cuploadify->file_dst_name;
        }
        

		die("{" . $this->Json->encode($json) . "}");
        // return $this->Cuploadify->upload();
	}//end action

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
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Email inválido'));
		}
		if ($this->Email->delete()) {
			$this->setAlert(__('Email deletado'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('O Email não pôde ser deletado'),false);
		$this->redirect(array('action' => 'index'));
	}
}//end Controller
?>
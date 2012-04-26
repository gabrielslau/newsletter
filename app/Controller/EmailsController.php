<?php
App::uses('AppController', 'Controller');
/**
 * Emails Controller
 *
 * @property Post $Post
 */
class EmailsController extends AppController {

	public $components = array('Upload','RequestHandler','Json','Cuploadify.Cuploadify');
	public $paginate   = array(
		'order'=>'id DESC',
		'cache' => true, 
		'cacheConfig' => 'long'
	);

	public function beforeFilter(){
		parent::beforeFilter();
		//Ações permitidas se o usuário não estiver logado
		$this->Auth->allowedActions = array('unsubscribe','validatelist');
	}


	/**
	 * validatelist method
	 *
	 * @return void
	 */
	function validatelist() {
		$this->autoRender = false;
		App::uses('Validation', 'Utility');
		App::uses('File', 'Utility');

		$validate = new Validation();
		$fileEmails = new File('files'.DS.'logs'.DS.'emails_invalidos.txt');

		$conditions = array('conditions'=>array('Email.enabled'=>true));

        if(!$fileEmails->exists()){
            $fileEmails->create();
        }else{
        	$content = $fileEmails->read();
        	if(!empty($content) && $content){

				$content = explode(';', $content);
				$content = array_filter($content, "checkEmpty"); //Limpa os campos vazios do array
				// Faz a filtragem na seleção dos emails para evitar duplicar os resultados no arquivo
				$conditions['conditions']['NOT'] = array(
					'Email.id'=>$content
				);
			}
        }

		$emails = $this->Email->find('list', $conditions );

		foreach($emails as $id=>$email){
			if( !$validate->email( $email, true ) ){
				$fileEmails->append($id.';');
			}
		}

	}//end index

	function disablelist() {
		$this->autoRender = false;
		App::uses('File', 'Utility');

		$fileEmails = new File('files'.DS.'logs'.DS.'emails_invalidos.txt');
		$content = $fileEmails->read();
		if(!empty($content) && $content){

			$content = explode(';', $content);
			$content = array_filter($content, "checkEmpty"); //Limpa os campos vazios do array

			if (!$this->Email->deleteAll( array('Email.id'=>$content), false )) {
	            die('Não foi possível deletar');
	        }else{
	        	$log = $this->Email->getDataSource()->getLog();debug($log);exit;
	        }
		}else{
			die('Não foi possível ler o arquivo.');
		}
	}//end index

	function unsubscribe($key=null){
		$this->layout = 'login';

		if ($this->request->is('post')) {
			$this->Email->recursive = -1;
			$email = $this->Email->findByEmail( $this->request->data['Email']['email'] );
			if( !empty($email) ){
				$this->Email->id = $email['Email']['id'];
				$this->Email->saveField('enabled',0);

				$this->set('mensagem','O email foi desativado. Você não receberá mais os nossos informativos');
			}else{
				$this->set('mensagem','Este email não está cadastrado em nosso sistema');
			}
		}elseif(!empty($key)){
			$this->Email->recursive = -1;
			$email = $this->Email->findByEmail( base64_decode($key) );
			if( !empty($email) ){
				$this->Email->id = $email['Email']['id'];
				$this->Email->saveField('enabled',0);

				$this->set('mensagem','O email foi desativado. Você não receberá mais os nossos informativos');
			}else{
				$this->set('mensagem','Este email não está cadastrado em nosso sistema');
			}
		}
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


	/**
	 * import method
	 *
	 * @return void
	 */
	public function import(){
		$this->autoRender = false;
		$this->layout     = 'ajax';

		// if($this->request->is('ajax') || 1==1){
		if($this->request->is('ajax')){
			App::uses('File', 'Utility');
			App::uses('Validation', 'Utility');

			$validate = new Validation();

			$file = new SplFileObject($this->request->data['path'].$this->request->data['filename']);
			// $file = new SplFileObject('files/tmp/newslettersemails-temoscasa.csv');
			// $this->request->data['categorias'] = array(1);
 
	        /* set file flags */
	        $file->setFlags(SplFileObject::DROP_NEW_LINE);

	        // Cria um arquivo para armazenar os erros da lista de emails enviada
	        $fileErrors = new File('files'.DS.'tmp'.DS.'lista_de_emails_com_erro_'.time().'.csv', true, 0644);

	        // TODO: Validar os dados e retornar os valores válidos e inválidos
			

			$newslettersemails_list = $this->Email->find('list');
			$Email = array(); // Guarda os dados dos emails e seus grupos que serão cadastrados/atualizados no sistema

			$erros = $emails_incluidos = array(); $numlinha = 1;
	        while ($file->valid()) {
	            $line = $file->fgets(); // get line from file 
	            $line = explode(",", addslashes(str_replace("\"", '', $line)));// Retira as aspas da string, para evitar erro no JSON AND split line value

		            // print_r($line);exit();

	             if( isset($line[0])){
		            if( !$validate->email($line[0],true) ) {
						// Insere o registro de erro no arquivo		            	
					    $fileErrors->append('O Email('.$line[0].'), não é válido');					    
		            	$erros[] = 'O Email('.$line[0].'), não é válido'; // die("{" . $this->Json->encode(array('msg'=>$line[0],'status'=>'error')) . "}");
		            }
		            else{
	            		if( !in_array($line[0], $emails_incluidos) ){
	            			$emails_incluidos[] = $line[0];

		            		// Monta os dados do email a cadastrar
		            		$Email[$numlinha]['Email'] = array(
		            			'email' => strtolower($line[0]),
		            			'nome' => ( empty($line[1]) ? trim($line[0]) : trim($line[1]) )
		            		);

		            		// Se o email já estiver cadastrado, seta o ID para que apenas atualize esse registro
		            		if( in_array($line[0], $newslettersemails_list) ){
		            			$Email[$numlinha]['Email']['id'] = array_search($line[0], $newslettersemails_list);
		            		}
							

		            		// Informa os grupos do email a cadastrar;
		            		// Caso nenhum grupo seja informado, seta por padrão o Grupo 1 (TODOS);
		            		$Email[$numlinha]['Group'] = ( isset($this->request->data['categorias']) && !empty($this->request->data['categorias']) ) ? $this->request->data['categorias'] : array('1');
	            		}
		            }
	            }

	            $numlinha++; // Numero de linhas do arquivo
	        }//end while


			// die("{" . $this->Json->encode( array('status'=>'error','msg'=>'POIA: '.$Email[1]['Group'][1]) ) . "}");
	        // print_r($Email);exit();
	        // die("{" . $this->Json->encode( array('status'=>'error','msg'=>'Grupos: '.$Email[1]['Group']) ) . "}");

	        if( empty($erros) ){
	        	$fileErrors->delete(); // I am deleting this file
	        }
	        $fileErrors->close(); // Be sure to close the file when you're done
	        // die("{" . $this->Json->encode( array('status'=>'error','msg'=>'POIA') ) . "}");


	        // Salva/Atualiza os dados no banco
	        if( !empty($Email) && $this->Email->saveAll($Email) ){

	        	// $log = $this->Email->getDataSource()->getLog();debug($log);exit;

	        	$msg = count($Email).' de '.$numlinha.' associados foram adicionados com sucesso.';
	        	CakeLog::write("debug", $msg);
	        	die("{" . $this->Json->encode(array('status'=>'success', 'erros'=>$erros, 'msg'=>$msg )) . "}");
	        }
	        else{
	        	$msg = 'Não foi possível adicionar os associados.';
	        	CakeLog::write("debug", $msg);
	        	die("{" . $this->Json->encode(array('status'=>'error', 'erros'=>$erros, 'msg'=>$msg )) . "}");
	        }

		}else{
			$msg = 'Não foi possível ler o arquivo com os associados.';
	        CakeLog::write("debug", $msg);
			die("{" . $this->Json->encode( array('status'=>'error','msg'=>$msg) ) . "}");
		}
	}//end function import()


	public function add() {
		$sessao_formulario = $this->Session->read('DadosEmailAdd'); // Sessão com os dados do formulário

		if ($this->request->is('post')) {

			// print_r($this->request->data);exit();

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


		/**
		 * Dados relacionados
		*/
		$groups         = $this->Email->Group->find('list',array('order'=>'nome ASC'));
		
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
<?php
App::uses('AppController', 'Controller');
/**
 * Newslettersemails Controller
 *
 * @property Post $Post
 */
class NewslettersemailsController extends AppController {

	var $components = array('Upload','RequestHandler','Json','Cuploadify.Cuploadify');

	public $paginate = array(
		'order'=>'id DESC'
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {

		// $this->Newslettersemail->recursive = 0;
		$this->paginate['cache'] = true;
		$this->set('newslettersemails', $this->paginate());

		$this->loadModel('Newslettersgroup');
		$this->set('newslettersgroups', $this->Newslettersgroup->find('list'));


		$css_for_layout = array('plugins/chosen/chosen','admin/core/button', 'View/newsletters/newsletters_index');
		$js_for_layout = array('plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min','plugins/chosen/chosen.jquery.min', 'View/newsletters/index');
		$this->set(compact('css_for_layout','js_for_layout'));
	}

/**
 * import method
 *
 * @return void
 */
	public function import($dados=null){
		if($this->request->is('ajax') || 1==1){
			App::uses('File', 'Utility');

			$file = new SplFileObject($this->request->data['path'].$this->request->data['filename']);
			// $file = new SplFileObject('files/tmp/tmp_1334171304-newslettersemails-unidental.csv');
 
	        /* set file flags */
	        $file->setFlags(SplFileObject::DROP_NEW_LINE);

	        // Cria um arquivo para armazenar os erros da lista de emails enviada
	        $fileErrors = new File('files'.DS.'tmp'.DS.'lista_de_emails_com_erro_'.time().'.csv', true, 0644);

	        // TODO: Validar os dados e retornar os valores válidos e inválidos
			// die("{" . $this->Json->encode( array('status'=>'error','msg'=>'POIA 2.'.$this->request->data['categorias'][0]) ) . "}");

			$this->loadModel('Newslettersemail');
			$newslettersemails_list = $this->Newslettersemail->find('list');

			
			// if( in_array('jmm3000@ig.com.br', $newslettersemails_list) ) exit('POIAA');
			// print_r($newslettersemails_list);exit();
			

			$Newslettersemail = array(); // Guarda os dados dos emails e seus grupos que serão cadastrados/atualizados no sistema

			$erros = $emails_incluidos = array(); $numlinha = 1;
	        while ($file->valid()) {
	            $line = $file->fgets();/* get line from file */
	            // $line = explode(",", $line);// Retira as aspas da string, para evitar erro no JSON AND split line value
	            $line = explode(",", addslashes(str_replace("\"", '', $line)));// Retira as aspas da string, para evitar erro no JSON AND split line value

		            // print_r($line);exit();

	             if( isset($line[3])){
		            if( !validarEmail($line[3]) ) {
						// Insere o registro de erro no arquivo		            	
					    $fileErrors->append('O Email('.$line[3].'), não é válido');					    
		            	$erros[] = 'O Email('.$line[3].'), não é válido'; // die("{" . $this->Json->encode(array('msg'=>$line[0],'status'=>'error')) . "}");
		            }
		            else{
	            		if( !in_array($line[3], $emails_incluidos) ){
	            			$emails_incluidos[] = $line[3];

		            		// Monta os dados do email a cadastrar
		            		$Newslettersemail[$numlinha]['Newslettersemail'] = array(
		            			'email' => strtolower($line[3]),
		            			'nome' => ( empty($line[2]) ? $line[3] : $line[2] )
		            		);

		            		// Se o email já estiver cadastrado, seta o ID para que apenas atualize esse registro
		            		if( in_array($line[3], $newslettersemails_list) ){
		            			$Newslettersemail[$numlinha]['Newslettersemail']['id'] = array_search($line[3], $newslettersemails_list);
		            		}

		            		// Informa os grupos do email a cadastrar;
		            		// Caso nenhum grupo seja informado, seta por padrão o Grupo 1 (TODOS);
		            		$Newslettersemail[$numlinha]['Newslettersgroup'] = ( isset($this->request->data['categorias']) && !empty($this->request->data['categorias']) ) ? $this->request->data['categorias'] : array('1');
	            		}
		            }
	            }

	            $numlinha++; // Numero de linhas do arquivo
	        }//end while

	        // if( !empty($Newslettersemail) ) {print_r($erros);exit();}
	        // print_r($Newslettersemail);exit();

	        if( empty($erros) ){
	        	$fileErrors->delete(); // I am deleting this file
	        }
	        $fileErrors->close(); // Be sure to close the file when you're done

	        // Salva/Atualiza os dados no banco
	        if( !empty($Newslettersemail) && $this->Newslettersemail->saveAll($Newslettersemail) ){
	        	die("{" . $this->Json->encode(array('status'=>'success', 'erros'=>$erros, 'msg'=>count($Newslettersemail).' de '.$numlinha.' associados foram adicionados com sucesso.' )) . "}");
	        }
	        else
	        	die("{" . $this->Json->encode(array('status'=>'error', 'erros'=>$erros, 'msg'=>'Não foi possível adicionar os associados.' )) . "}");

		}else 
			die("{" . $this->Json->encode( array('status'=>'error','msg'=>'Não foi possível ler o arquivo com os associados.') ) . "}");
	}//end function import()


/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->loadModel('Newslettersemail');


		$this->Newslettersemail->id = $id;
		if (!$this->Newslettersemail->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}

		$newsletter = $this->Newslettersemail->read(null, $id);

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
		$sessao_formulario = $this->Session->read('DadosEmailAdd'); // Sessão com os dados do formulário

		if ($this->request->is('post')) {

			// print_r($this->request->data);exit();

			$this->Newslettersemail->set($this->request->data);
			$this->Newslettersemail->validationSet = 'CadastroEmail';
			if($this->Newslettersemail->validates()){
				
				$this->request->data['Newslettersgroup'] = $this->request->data['Newslettersgroup']['id'];
				// print_r($this->request->data);exit();

				$this->Newslettersemail->create();
				if ($this->Newslettersemail->saveAll($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}
			/*else{
				print_r($this->Newslettersemail->invalidFields());exit();
			}*/
		}

		$this->loadModel('Newslettersgroup');
		$newslettersgroups = $this->Newslettersgroup->find('list',array('conditions'=>array('id>1')));

		$css_for_layout = array('admin/core/form','admin/core/button','plugins/chosen/chosen');
		$js_for_layout = array('plugins/chosen/chosen.jquery.min', 'View/newsletters/add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','newslettersgroups'));
	}	

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {

		$this->Newslettersemail->id = $id;
		if (!$this->Newslettersemail->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			// print_r($this->request->data);exit();

			$this->Newslettersemail->set($this->request->data);
			$this->Newslettersemail->validationSet = 'CadastroEmail';
			if($this->Newslettersemail->validates()){

				$this->request->data['Newslettersgroup'] = $this->request->data['Newslettersgroup']['id'];

				if ($this->Newslettersemail->saveAll($this->request->data)) {
					$this->setAlert(__('The newsletter has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}
			else{
				print_r($this->Newslettersemail->invalidFields());exit();
			}
		} else {
			$this->Newslettersemail->recursive = -1;
			$this->request->data = $this->Newslettersemail->read(null, $id);
			// print_r($this->request->data);exit();
		}

		$this->loadModel('Newslettersgroup');
		$newslettersgroups = $this->Newslettersgroup->find('list',array('conditions'=>array('id>1')));

		$css_for_layout = array('admin/core/form','admin/core/button','plugins/chosen/chosen');
		$js_for_layout = array('plugins/chosen/chosen.jquery.min', 'View/pages/gerencia_add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','newslettersgroups'));

		// $this->render('add');
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
		$this->Newslettersemail->id = $id;
		if (!$this->Newslettersemail->exists()) {
			throw new NotFoundException(__('Email inválido'));
		}
		if ($this->Newslettersemail->delete()) {
			$this->setAlert(__('Email deletado'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('O Email não pôde ser deletado'),false);
		$this->redirect(array('action' => 'index'));
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



} // end Controller
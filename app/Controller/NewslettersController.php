<?php
App::uses('AppController', 'Controller');
/**
 * Newsletters Controller
 *
 * @property Post $Post
 */
class NewslettersController extends AppController {

	public $helper = array('Time');
	public $components = array('Newsletterdispatch');
	public $paginate = array(
		'order'=>'Newsletter.date_send DESC'
	);

	public function beforeFilter(){
		//Ações permitidas se o usuário não estiver logado
		$this->Auth->allowedActions = array('view','newsletter_dispatch','disableEmails');
	}


	/*public function disableEmails(){
		$this->autoRender = false;
		$this->layout = 'ajax';
		$listaemails = 'mmsocorromelo@hotmail.com;jorge.sdf@hotmail.com;jeaneslooes@ig.com.br;jcg_6@hotmail.com;zetefs@yahoo.com.br;jadnafms@hotmail.com;mardeiros@hotmail.com;ive.machado@hotmail.com;ma.raj.santos@hotmail.com;mgracafernandes@hotmail.com;marialuizacmc@hotmail.com;galvesfernandes@yahoo.com.br;mardeiros@hotmal.com;gabysalgado02@hotmail.com;marcusfal@hotmail.com;fla_azeoliveira@hotmail.com;mdantas7@bol.com.br;ffatima.dantas@hotmail.com;ettna@hotmail.com;joasmanso@hotmail.com;elzadn@hotmail.com;marcia.oliveira54@hotmail.com;docarmosevero@yahoo.com.br;magnalda@hotmail.com;ferreira.rh@hotmail.com;luzeildeandrade@hotmail.com;diego_lunna@hotmail.com;aymoneluiz@hotmail.com;cristinabronzo@yahoo.com.br;luizaugusto201158@hotmail.com;cristianolemes.rn@hotmail.com;dudu-b1@live.com;cilnat@hotmail.com;luci710@hotmail.com;indya_natalrn@hotmail.com;lrgandrade@gmail.com;hmelo@supercabo.com.br;liduinahr@hotmail.com;atlas.rn@atlastranslog.com.br;ledasales@hotmail.com;lania_n@hotmail.com;laisemso@hotmail.com;kash@kashinvest.com.br;junioraraujo262@hotmail.com;jsamuelmedeiros@hotmail.com;rodrigues@live.de;elieneroque@yahoo.com.br;josealentejano869@hotmail.com;marlow2011.1@hotmail.com;jorge.sdf@hotmail.com;jeaneslooes@ig.com.br;jcg_6@hotmail.com;jadnafms@hotmail.com;ive.machado@hotmail.com;mgracafernandes@hotmail.com;galvesfernandes@yahoo.com.br;gabysalgado02@hotmail.com;fla_azeoliveira@hotmail.com;ffatima.dantas@hotmail.com;ettna@hotmail.com;elzadn@hotmail.com;docarmosevero@yahoo.com.br;ferreira.rh@hotmail.com;diego_lunna@hotmail.com;cristinabronzo@yahoo.com.br;cristianolemes.rn@hotmail.com;cilnat@hotmail.com;indya_natalrn@hotmail.com;hmelo@supercabo.com.br;atlas.rn@atlastranslog.com.brn';
		$listaemails = explode(';', $listaemails);
		$this->Newsletter->Email->recursive = -1;

		if($this->Newsletter->Email->updateAll(array("`Email`.`status`" => "'0'"), array('`Email`.`email`'=>$listaemails))){
			echo 'OK';
		}else echo 'POIA';

		// $log = $this->Newsletter->Email->getDataSource()->getLog();debug($log);exit;
	}*/

	public function newsletter_dispatch(){
		$this->autoRender = false;
		$this->layout = 'ajax';
		$this->Newsletterdispatch->send();

		echo $this->Newsletterdispatch->getLog();
		/*if( $this->Newsletterdispatch->sended ){
			echo $this->Newsletterdispatch->getLog();
		}
		else echo $this->Newsletterdispatch->getError();*/
	}




	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Newsletter->recursive = -1;
		$this->Newsletter->Behaviors->attach('Containable');
		$this->paginate['contain'] = array('Group','Template');
		$this->set('newsletters', $this->paginate());

		$css_for_layout = array(/*'View/newsletters/newsletters_index',*/'admin/core/button');
		$this->set(compact('css_for_layout'));
	}

	/**
	 * view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('%s inválida.', true), 'Newsletter'));
			$this->redirect(array('action' => 'index'));
		}
		$newsletter = $this->Newsletter->read(null, $id);

		// Enable CacheView
		$this->helpers[] = 'Cache';
		$this->cacheAction = '1 day';

		App::uses('File', 'Utility');
		$path_layout = 'Emails'.DS.'html'.DS;
		$file = new File(APP.'View'.DS.'Layouts'.DS. $path_layout.$newsletter['Template']['file'].'.ctp');

		//Seta o layout padrão, caso não exista um
		$this->layout = $file->exists() ? $path_layout.$newsletter['Template']['file'] : $path_layout.'newsletter_default';
		$this->set(compact('newsletter'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */

	// TODO: Escolher template
	
	public function add() {
		$sessao_formulario = $this->Session->read('DadosNewsAdd'); // Sessão com os dados do formulário

		if ($this->request->is('post') || $this->request->is('put')) {
			// Dados relacionados
			$this->request->data['Group'] = !empty($this->request->data['Group']['id']) ? $this->request->data['Group']['id'] : array();
			$this->request->data['Email'] = !empty($this->request->data['Email']['id']) ? $this->request->data['Email']['id'] : array();
			// $this->request->data['Queue'] = $this->setQueue(null, $this->request->data['Group'],$this->request->data['Email']); // Organiza os emails para a lista de recebimento da news
			
			// print_r($this->request->data['Queue']);exit();

			$this->Newsletter->set($this->request->data);
			$this->Newsletter->validationSet = 'CadastroNews';
			if($this->Newsletter->validates()){
				$this->request->data['Newsletter']['created'] = date('Y-m-d H:i:s');
				$this->request->data['Newsletter']['user_id'] = AuthComponent::user('id');

				/*$this->Newsletter->unbindModel(array(
					'hasMany' => array('Log'),
					'belongsTo' => array('User','Template')
				));*/


				$this->Newsletter->create();
				if ($this->Newsletter->saveAll($this->request->data)) {
					
					// Salva a fila de envio
					// Organiza os emails para a lista de recebimento da news
					/*if($this->setQueue($this->Newsletter->id, $this->request->data['Group'],$this->request->data['Email'])){
						$this->setAlert(__('A newsletter foi salva mas a fila de envio não pôde ser gerada'));
					}
					else $this->setAlert(__('A newsletter foi salva com sucesso'));*/




					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('A newsletter não pôde ser salva'),false);
				}
			}
			/*else{
				print_r($this->Newsletter->invalidFields());exit();
			}*/
		}

		$css_for_layout = array('admin/core/form','admin/core/button','plugins/timepicker/timepicker','plugins/chosen/chosen' /*'plugins/uploadify/uploadify'*/);
		$js_for_layout = array('plugins/chosen/chosen.jquery.min','ckeditor/ckeditor','ckeditor/adapters/jquery', 'plugins/timepicker/timepicker', /*'plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min',*/'View/newsletters/add');


		/**
		 * Dados relacionados
		*/
		// $users = $this->Newsletter->User->find('list');
		$templates = $this->Newsletter->Template->find('list');
		$emails    = $this->Newsletter->Email->find('list');
		$groups    = $this->Newsletter->Group->find('list', array('conditions'=>array('email_count > 0')));
		$title_for_layout = 'Cadastrar nova Newsletter';

		$this->set(compact('templates', 'emails', 'groups','css_for_layout','js_for_layout','sessao_formulario','title_for_layout'));
	}//end add



	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->Newsletter->id = $id;
		if (!$this->Newsletter->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			// Dados relacionados
			$this->request->data['Group'] = !empty($this->request->data['Group']['id']) ? $this->request->data['Group']['id'] : array();
			$this->request->data['Email'] = !empty($this->request->data['Email']['id']) ? $this->request->data['Email']['id'] : array();
			// $this->request->data['Queue'] = $this->setQueue($id, $this->request->data['Group'],$this->request->data['Email']); // Organiza os emails para a lista de recebimento da news

			// print_r($this->request->data);exit();

			$this->Newsletter->set($this->request->data);
			$this->Newsletter->validationSet = 'CadastroNews';
			if($this->Newsletter->validates()){

				if ($this->Newsletter->saveAll($this->request->data)) {
					$this->setAlert(__('A newsletter foi salva'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->setAlert(__('The newsletter could not be saved. Please, try again.'),false);
				}
			}else{

			}
		} else {
			// $this->Newsletter->recursive = -1;
			// Remove relacionamento dos Models que não serão usados nessa consulta
			$this->Newsletter->unbindModel(array(
				'hasMany' => array('Queue','Log')
			));
			$this->request->data = $this->Newsletter->read(null, $id);
			// print_r($this->request->data);exit();
		}


		$css_for_layout = array('admin/core/form','admin/core/button','plugins/timepicker/timepicker','plugins/chosen/chosen' /*'plugins/uploadify/uploadify'*/);
		$js_for_layout = array('plugins/chosen/chosen.jquery.min','ckeditor/ckeditor','ckeditor/adapters/jquery', 'plugins/timepicker/timepicker', /*'plugins/swfobject','plugins/uploadify/jquery.uploadify.v2.1.4.min',*/'View/newsletters/add');

		$this->set(compact('css_for_layout','js_for_layout','sessao_formulario','newslettersgroups'));


		/**
		 * Dados relacionados
		*/
		// $users = $this->Newsletter->User->find('list');
		$templates = $this->Newsletter->Template->find('list');
		$emails    = $this->Newsletter->Email->find('list');
		$groups    = $this->Newsletter->Group->find('list', array('conditions'=>array('email_count > 0')));
		$title_for_layout = 'Atualizar Newsletter';
		$this->set(compact('templates', 'emails', 'groups','title_for_layout'));

		$this->render('add');
	}//end edit



	/**
	 * setQueue method
	 * 
	 * Organiza uma lista de emails únicos vindo de uma lista de grupos ou de emails
	 *
	 * @param int $id
	 * @param array $groups
	 * @param array $emails
	 * @return boolean || array
	 */	
	private function setQueue($id = null, $groups = null, $emails = null){
		$lista_de_emails = array(); // Armazena uma lista de emails únicos
		$i = 0; //contador geral
		


		// armazena os emails na lista
		if(!empty($emails)):
			foreach ($emails as $emailId) {
				if( !in_array_r($emailId, $lista_de_emails) ){
					if(!empty($id)) $lista_de_emails['Queue'][$i]['newsletter_id'] = $id;
					$lista_de_emails['Queue'][$i]['email_id']                      = $emailId;
					$i++;
				}
			}

			if(empty($groups)){
				// Salva a queue só para os emails
				$this->saveQueue($lista_de_emails);
			}
		endif;


		// Procura emails na lista de grupos
		if(!empty($groups)):
			$this->Newsletter->Group->Behaviors->attach('Containable');
			$limit    = 500; // Seleciona 500 resultados por vez
			$offset   = 0; 
			$continue = true;

			do{
				$EmailsInGroup = $this->Newsletter->Group->find('all',array('conditions'=>array( 'Group.id'=>$groups ), 'contain'=>array('Email'=>array('fields'=>array('id'),'limit'=>$limit,'offset'=>$offset))  ));

				// print_r($EmailsInGroup);exit();

				// Impede o script de continuar rodando se não tiver mais emails a cadastrar
				if( empty($EmailsInGroup) ){
					$continue = false;
					$lista_de_emails = array();
				}

				if($continue):
					// armazena os emails na lista
					foreach ($EmailsInGroup as $group) {
						foreach ($group['Email'] as $email){
							if( !in_array_r($email['id'], $lista_de_emails) ){
								if(!empty($id)) $lista_de_emails['Queue'][$i]['newsletter_id'] = $id;
								$lista_de_emails['Queue'][$i]['email_id']                      = $email['id'];
								$i++;
							}
						}
					}
					$offset += $limit;

					// salva
					$this->saveQueue($lista_de_emails);

				endif;
			}while ($continue);

			
		endif;
	}

	/**
	 * saveQueue method
	 * 
	 * Salva uma lista de emails únicos na fila de envio da newsletter
	 *
	 * @param array $dataQueue
	 * @return boolean
	 */	
	private function saveQueue($dataQueue){
		return $this->Newsletter->Queue->save($dataQueue);
	}



	/**
	 * enable method
	 * 
	 * Reativa a newsletter e coloca novamente na fila de envio (  TODO  )
	 *
	 * @param string $id
	 * @return void
	 */	
	public function enable($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Newsletter->id = $id;
		if (!$this->Newsletter->exists()) {
			throw new NotFoundException(__('Newsletter inválida'));
		}

		if ($this->Newsletter->saveField('status',1)) {
			$this->setAlert(__('A Newsletter foi reativada'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('A Newsletter não pôde ser reativada'),false);
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * disable method
	 * 
	 * Desativa a newsletter para que não possa mais ser enviada ( apenas visualizada )
	 *
	 * @param string $id
	 * @return void
	 */	
	public function disable($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Newsletter->id = $id;
		if (!$this->Newsletter->exists()) {
			throw new NotFoundException(__('Newsletter inválida'));
		}

		if ($this->Newsletter->saveField('status',0)) {
			$this->setAlert(__('A Newsletter foi desativada. Agora só está acessível para visualização'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('A Newsletter não pôde ser desativada'),false);
		$this->redirect(array('action' => 'index'));
	}


	/**
	 * delete method
	 * 
	 * Exclui a newsletter definitivamente do sistema (método acessado somente pelo administrador)
	 *
	 * @param string $id
	 * @return void
	 */	
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Newsletter->id = $id;
		if (!$this->Newsletter->exists()) {
			throw new NotFoundException(__('Newsletter inválida'));
		}
		if ($this->Newsletter->delete()) {
			$this->setAlert(__('Newsletter excluída definitivamente.'));
			$this->redirect(array('action'=>'index'));
		}
		$this->setAlert(__('A Newsletter não pôde ser excluída excluída'),false);
		$this->redirect(array('action' => 'index'));
	}
}//end Controller
?>
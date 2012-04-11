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

	public function beforeFilter(){
		
		$this->Auth->allowedActions = array('view','enviar_agendadas_simples','newsletter_dispatch'); //Ações permitidas se o usuário não estiver logado

	}


	function newsletter_dispatch(){
		$this->autoRender = false;
		$this->layout = 'ajax';
		$this->Newsletterdispatch->send();

		/*if( $this->Newsletterdispatch->sended ){
			echo $this->Newsletterdispatch->getLog();
		}else echo $this->Newsletterdispatch->getError();*/
	}



	function enviar_agendadas_simples() {
		
		$this->loadModel('Newslettersemail');
        $this->loadModel('Newslettersgroup');
        $this->loadModel('Newslettersqueue');
        $this->loadModel('Newsletterslog');

        $max_sent_per_hour = 500;     // Máximo de emails enviados por vez





        /**
         * Verifica se há alguma newsletter agendada para hoje
        */
        	// TODO: Fazer a verificação a nível de hora e minuto

        $this->Newslettersqueue->Behaviors->attach('Containable');
        $Newslettersqueue = $this->Newslettersqueue->find('first', array(
        	'conditions'=>array(
        		"`status` = '0'",
        		"CAST(Newslettersqueue.data_envio AS DATE) = CAST( NOW() AS DATE )"
        	),
        	'contain'=>array(
        		'Newslettersuser',
        		'Newslettersgroup'=>array(
        			'Newslettersemail'=>array(
        				'conditions'=>array("Newslettersemail.status = '0'"),
        				'limit'=>$max_sent_per_hour
        			)
        		)
        	)
        ));

        // $log = $this->Newslettersqueue->getDataSource()->getLog();debug($log);exit;

        // print_r($Newslettersqueue);exit();

        $lista_de_destinatarios = array();

        /**
         * Procura os emails dos grupos e monta uma lista de emails únicos para enviar
        */
        foreach ($Newslettersqueue['Newslettersgroup'] as $group) {
        	foreach ($group['Newslettersemail'] as $email) {
        		if( !in_array_r($email['email'], $lista_de_destinatarios) )
        			$lista_de_destinatarios[] = array(
        				'id' => $email['id'],
        				'nome' => $email['nome'],
        				'email' => $email['email']
        			);
        	}
        }
        // print_r($lista_de_destinatarios);exit();

        /**
         * Manda o email para a lista de emails selecionados
        */

        App::uses('CakeEmail', 'Network/Email');
        foreach ($lista_de_destinatarios as $email) {
        	
			$email = new CakeEmail('smtp');
			$email->to($email['email'])
			// ->replyTo(array($email['email']=>$email['email']))
			->from(array(MAIL_REMETENTE => MAIL_REMETENTENAME))
			->template('newsletter', 'Emails'.DS.'html'.DS.'newsletter_'.$Newslettersqueue['Newslettersuser']['username'])
			->emailFormat('html')
			->subject( $Newslettersqueue['Newslettersqueue']['subject'] )
			->viewVars(
				array(
					'message' => $Newslettersqueue['Newslettersqueue']['emailbody'],
				)
			);

			if(!$email->send()){
				CakeLog::write("debug", "Email de ".$Newslettersqueue['Newslettersuser']['nome']." enviado para ".$email['email']." na Newslettersqueue # ".$Newslettersqueue['Newslettersqueue']['id']." falhou.");
			}else{
				// A newsletter foi enviada, então atualiza o ID
				$this->Newslettersemail->id = $email['id'];
				$this->Newslettersemail->saveField('status',true);



			}
        }




		$this->Newsletter->tipo_news = 'agendada';
		$this->Newsletter->send();
		if($this->Newsletter->sended){echo $this->Newsletter->getLog();}
		else{echo $this->Newsletter->getError();}


		exit;


		$this->set('newslettersagendadas', $this->paginate());
	}



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


		$this->Newslettersqueue->id = $id;
		if (!$this->Newslettersqueue->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}

		$newsletter = $this->Newslettersqueue->read(null, $id);

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
			/*else{
				print_r($this->Newslettersqueue->invalidFields());exit();
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
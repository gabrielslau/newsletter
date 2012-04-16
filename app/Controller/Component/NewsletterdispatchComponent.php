<?php
/**
 * Class Newsletterdispatch Component
 *
 * Classe para envio de newsletter em massa
 * 
 * @version   2.0
 * @author    Gabriel (Okatsura) Lau <gabrielslau@yahoo.com.br>
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Gabriel (Okatsura) Lau
 */

class NewsletterdispatchComponent extends Component {

    var $name = 'Newsletterdispatch';
    var $components = array('Session', 'Email');

    /**
     * Class version
     *
     * @access public
     * @var string
     */
    var $version;

    /**
     * The instantiating controller.
     *
     * @var boolean
     * @access public
     */
    var $controller;

    /**
     * Quantidade de mensagens enviadas de cada vez 
     * 
     * @access public
     * @var integer
     */
     // maximum sent emails by time
    var $max_sent_per_hour;
    
    /**
     * Tempo entre o envio de um pacote e outro (em segundos)
     * 
     * @access public
     * @var integer
     */
    var $sec;

    /**
     * Limite de emails por hora
     * 
     * @access public
     * @var integer
     */
    var $limitMail;
    
    /**
     * Tempo de pausa por hora (40 minutos = 2400 segundos)
     * 
     * @access public
     * @var integer
     */
    var $secLimitMail;
    
    /**
     * Guarda o ID do log de mensagens enviadas
     * 
     * @access public
     * @var integer
     */
    var $id_enviada;

    /**
     * Guarda o ID da nesletter a ser enviada
     * 
     * @access public
     * @var integer
     */
    var $id_newsagendada;


    /**
     * Guarda o número de emails que foram enviados
     * 
     * @access public
     * @var integer
     */
    var $total_enviados;

    /**
     * Guarda o número de emails cadastrados no sistema
     * 
     * @access public
     * @var integer
    */
    var $total_emails;

    /**
     * Guarda o número de emails que faltam receber o informativo
     * 
     * @access public
     * @var integer
    */
    var $total_destinatarios_in_queue;

    /**
     * Guarda os emails que faltam receber o informativo
     * 
     * @access public
     * @var array
    */
    public $destinatarios;

    /**
     * Guarda o número de newsletters para enviar
     * 
     * @access public
     * @var integer
     */
    var $total_newsletters;

    /**
     * Tipos permitidos de newsletters
     * 
     * @access private
     * @var array
     */
    var $tipos_permitidos;

    /**
     * Tipo de envio da newsletter: se não for informado, agendada é o valor padrao
     * 
     * @access public
     * @var string
     */
    var $tipo_news;

    /**
     * Data do início da operação
     * 
     * @var string
     */
    var $data_atual;


    /**
     * Se pode continuar com as operações ou não
     *
     *
     * @access public
     * @var bool
     */
    var $proceed;

    /**
     * Flag set after calling a process
     *
     * Indicates if the processing, and copy of the resulting file went OK
     *
     * @access public
     * @var bool
     */
    var $sended;

    /**
     * Holds eventual error message
     *
     * @access public
     * @var array
     */
    var $error;

    /**
     * Holds an HTML formatted log
     *
     * @access public
     * @var array
     */
    var $log;

    /**
    * Objeto do Model Email
    *
    * @access private
    * @var object
    */
    var $Email;

    /**
    * Objeto do Model Log
    *
    * @access private
    * @var object
    */
    var $Log;
    /**
    * Objeto do Model Queue
    *
    * @access private
    * @var object
    */
    var $Queue;


    /**
     * Init or re-init all the processing variables to their default values
     *
     * This function is called in the constructor, and after each call of {@link process}
     *
     * @access private
     */
    function initialize(&$controller, $settings = array()){
        // saving the controller reference for later use
        $this->controller = $controller;

        // overiddable variables
        $this->max_sent_per_hour   = 400;           // Máximo de emails enviados por vez (o limite da locaweb é 500, mas utilizamos a menos por garantia)
        //$this->sec               = 10;            // Tempo entre o envio de um pacote e outro (em segundos)
        //$this->limitMail         = 500;           // Limite de emails por hora
        //$this->secLimitMail      = 2400;          // Tempo de pausa por hora (40 minutos = 2400 segundos)
        
        $this->total_newsletters         = 0;             // Número de newsletters para enviar
        // $this->Queue               = array();       // Lista de newsletters agendadas e seus Models relacionados
        $this->destinatarios       = array();       // Lista de emails de quem falta receber a newsletter
        
        
        
        
        $this->id_enviada          = null;          // ID do log de envios de newsletters do dia
        
        $this->total_enviados      = 0;             // Enviados até o momento
        $this->total_emails        = 0;             // Remetentes cadastrados no sistema
        $this->total_destinatarios_in_queue = 0;             // Remetentes que faltam receber o informativo
        $this->total_destinatarios_sliced = 0;             // Remetentes
        
        $this->subject             = '';            // Assunto do email
        $this->email_body          = '';            // Corpo da news
        
        $this->proceed             = true;          // Se pode continuar com as operações ou não
        $this->sended              = true;          // Se foi enviada ou não
        $this->data_atual          = date('d/m/Y'); // Data de início da operação
        $this->log                 = array();       // Registro de mensagens do sistema
        $this->error               = array();       // Registro de mensagens de erro do sistema
        
        // Models a utilizar
        // $this->Email               = ClassRegistry::init('Email');
        // $this->Group               = ClassRegistry::init('Group');
        // $this->Queue               = ClassRegistry::init('Queue');
        // $this->Log                 = ClassRegistry::init('Log');
    }


    /**
     * Envia a newsletter para os grupos de emails selecionados
     * 
     * @uses Newsletter::reset()
     * @uses Newsletter::getNewslettersQueue()
     * @access public
     *
     * @return boolean : Enviou ou não?
     */
    public function send(){
        $this->getNewslettersQueue();   // Newsletters na fila para enviar
        $this->getDestinatarios();      // Lista de emails nos grupos selecionados que faltam receber a newsletter
        
        if( $this->proceed ){
            $this->setLog("Pronto pra mandar a news");

            if($this->total_destinatarios_in_queue > 0){
               
                /**
                 * Manda o email para a lista de emails selecionados
                */

                App::uses('CakeEmail', 'Network/Email');
                

                $lista_de_destinatarios = $ids_queue = array();
                foreach ($this->destinatarios as $destinatario) {
                    // if( validarEmail( $destinatario['Email']['email'] ) ) $lista_de_destinatarios[] = array($destinatario['Email']['email']=>$destinatario['Email']['nome'] );
                    if( validarEmail( $destinatario['Email']['email'] ) ) {
                        $ids_queue[]              = $destinatario['id']; // guarda o ID da queue que recebeu o email, para eliminar da lista
                        $lista_de_destinatarios[] = $destinatario['Email']['email'];
                    }
                }

                // Grava o primeiro email como destinatário principal e remove-o da lista
                $send_to = array_slice( $lista_de_destinatarios, 0,1 );
                unset( $lista_de_destinatarios[ key($send_to) ] );
                // die( current($send_to[0]) );
                // print_r($send_to);exit();


                $CakeEmail = new CakeEmail('smtp');
                $CakeEmail->to( $send_to[0] )
                // ->from( current($send_to[0]) )
                ->template('newsletter', $this->Newsletter['Template']['file'] )
                ->bcc( $lista_de_destinatarios )
                ->emailFormat('html')
                ->subject( $this->Newsletter['Newsletter']['subject'] )
                ->viewVars(
                    array(
                        'message' => $this->Newsletter['Newsletter']['emailbody'],
                        // 'QueueId'=>$Queue['Queue']['id'],
                        // 'send_to'=>$email['email'],
                        'show_full_html'=>false
                    )
                );

                if(!$CakeEmail->send()){
                    $this->sended = false;
                    $this->setLog("Email de ".$this->Newsletter['User']['nome']." na Newsletter # ".$this->Newsletter['Newsletter']['id']." falhou.");
                    // $this->setLog("Email de ".$this->Newsletter['User']['nome']." enviado para ".$email['email']." na this->Newsletter # ".$this->Newsletter['Queue']['id']." falhou.");
                }else{
                    // A newsletter foi enviada, cria Log de registro

                    $sent     = 0; //Enviadas até o momento
                    $ModelLog = ClassRegistry::init('Log');

                    if( empty($this->Newsletter['Log']) ){
                        $ModelLog->create();
                        $ModelLog->set('start_sending', date('Y-m-d H:i:s'));
                    }else{
                        $ModelLog->id = $this->Newsletter['Log'][0]['id'];
                        $sent         = $this->Newsletter['Log'][0]['sent'];
                    }

                    $ModelLog->set('newsletter_id', $this->Newsletter['Newsletter']['id']);
                    $ModelLog->set('sent', $sent + $this->total_destinatarios_sliced ); // Contador de newsletters enviadas
                    $ModelLog->save();


                    // Limpa os emails da fila
                    $ModelQueue = ClassRegistry::init('Queue');
                    $conditions = array(
                        'newsletter_id'=>$this->Newsletter['Newsletter']['id'],
                        'email_id'=>$ids_queue
                    );

                    if (!$ModelQueue->deleteAll($conditions,true)) {
                        $this->setLog("Não foi possível excluir a lista de emails da fila de espera");
                    }else{
                        $this->setLog( count($ids_queue) ." emails foram excluídos da fila de espera");
                    }


                    $this->setLog("Newsletter # ".$this->Newsletter['Newsletter']['id'].' enviada em '.date('Y-m-d H:i:s'));
                }// end CakeMail->send()
            }//end total_destinatarios_in_queue > 0
        }else{
            $this->setLog("Não foi possível enviar a Newsletter");
            return false;
        }
    }// end function send()

    /**
    * Seleciona alguma newsletter na fila de envio para o dia atual
    * 
    * Seleciona apenas a primeira newsletter para evitar ultrapassar o limite de envio de emails do SMTP
    * 
    * @access public
    * @return void
    */
    public function getNewslettersQueue(){
        $ModelNewsletter       = ClassRegistry::init('Newsletter');

        $ModelNewsletter->Behaviors->attach('Containable');
        $this->Newsletter = $ModelNewsletter->find('first', array(
            'conditions'=>array(
                "`Newsletter`.`status` = '1'",
                "CAST(Newsletter.date_send AS DATE) = CAST( NOW() AS DATE )"
            ),
            'order'=>'Newsletter.date_send ASC',
            'contain'=>array(
                'User','Log','Template',
                'Queue'=>array(
                    'Email'=>array(
                        'conditions'=>array("Email.status = '1'")/*,
                        'limit'=>$this->max_sent_per_hour*/
                        // A limitação do número de emails é feita na hora do envio
                    )
                )
            )
        ));
        // print_r($this->Newsletter);exit();

        // $this->total_newsletters = count($this->Newsletter['Newsletter']);
        $this->total_newsletters = !empty($this->Newsletter) ? 1 : 0;
        $this->setLog("Total de newsletters na fila de envio em ($this->data_atual): $this->total_newsletters");

        // Se não tiver newsletter programada para envio, impede que as outras operações sejam realizadas
        if($this->total_newsletters == 0) $this->proceed = false; 
    } //end getNewslettersQueue

    /**
    * Pega o total de emails nos grupos que faltam receber a newsletter
    * 
    * @access public
    * @return void
    */
    public function getDestinatarios(){
        // Só realiza a operação se tiver alguma newsletter na fila de envio
        if($this->proceed){
            /**
             * Procura os emails dos grupos e monta uma lista de emails únicos para enviar
            */
            $this->total_destinatarios_in_queue = count($this->Newsletter['Queue']);
            $this->setLog('Total de destinatários disponíveis para envio da news: '.$this->total_destinatarios_in_queue);
            
            if( $this->total_destinatarios_in_queue == 0 ){
                // Se não tiver emails para o envio da news, impede que as outras operações sejam realizadas
                // e desativa a newsletter da fila de envio
                $this->proceed = false;
                $this->disableNewsletterQueue();

            }else{
                // pega o máximo de emails que pode receber a newsletter, por hora
                $this->destinatarios = array_slice($this->Newsletter['Queue'], 0, $this->max_sent_per_hour); 
                $this->total_destinatarios_sliced = count($this->destinatarios);
            }
            unset($this->Newsletter['Queue']); //Limpa o array de emails e grupos para liberar memoria
        }
    } //end getDestinatarios()

    /**
    * Desabilita a newsletter atual
    * 
    * @access private
    * @return void
    */
    private function disableNewsletterQueue(){
        $ModelNewsletter     = ClassRegistry::init('Newsletter');
        $ModelNewsletter->id = $this->Newsletter['Newsletter']['id'];

        if (!$ModelNewsletter->saveField('status',0)) {
            $this->setLog( __('A Newsletter # %s não pôde ser desativada. ', $this->Newsletter['Newsletter']['id'] ));
        }else{
            $this->setLog( __('A newsletter # %s foi enviada para todos os remetentes e foi desabilitada. ', $this->Newsletter['Newsletter']['id'] ));

        }
    } //end disableNewsletterQueue()



    /**
    * Atualiza o contador da quantidade de newsletters enviadas
    * 
    * @access private
    */
    private function upCountNews($id=null,$count=0){
        if($this->proceed){

            if(!$this->total_enviados>0){
                $this->Log->create();
                $this->Log->set('newslettersqueue_id', $id);
                $this->Log->set('numero_enviados', $count);
                $this->Log->set('sended', date('Y-m-d H:i:s'));
                $this->Log->save();
                // die('ENVIDAOS >! 0');
            }else{
                $this->Log->id = $this->id_enviada;
                $this->Log->set('newslettersqueue_id', $id);
                $this->Log->saveField('numero_enviados',$count);
                // die('ENVIADOS > 0');
            }
            
            //$this->Queue->save();
            $this->log[] = 'Total de newsletters enviadas em '.$this->data_atual.' atualizado para '.$count;
            CakeLog::write("debug", 'Total de newsletters enviadas em '.$this->data_atual.' atualizado para '.$count);
            // die('LOG');
        }
        // die('TESTE');
    }

    /**
    * Pega o total de newslleters que já foram enviadas hoje
    * 
    * @access private
    *
    * @return integer : Total de enviados
    */
/*    function getTotalEnviadasHoje(){
        //$tablename = $Log->table;
        $options['fields'] = array('id',"numero_enviados",'newslettersqueue_id');
        $options['recursive'] = -1;
        $options['conditions'] = array(
            // "DATE_FORMAT(`sended`,'%d/%m/%Y') = DATE_FORMAT(NOW(),'%d/%m/%Y')"
            "CAST(sended AS DATE) = CAST( NOW() AS DATE )"
        );
        //$this->total_enviados = $Log->find('count',$options);
        $enviadas = $this->Log->find('first',$options);
        // $log = $this->Log->getDataSource()->getLog();debug($log);exit;
        $this->total_enviados = ($enviadas['Log']['numero_enviados'] > 0 ? $enviadas['Log']['numero_enviados'] : 0);
        $this->id_enviada = $enviadas['Log']['id'];
        $this->id_newsagendada = $enviadas['Log']['newslettersqueue_id'];
        $this->log[] = '<b>Total de newsletters enviadas hoje:</b> '.$this->total_enviados;
        CakeLog::write("debug", "Total de newsletters enviadas hoje: ".$this->total_enviados);
    }
*/





    /**
    * Guarda as mensagens de log do sistema
    *
    * @access private
    * @uses CakeLog::write()
    * @return void
    */
    private function setLog($mensagem=null){
        if(!empty($mensagem)){
            $this->log[] = $mensagem;
            CakeLog::write("debug", $mensagem);
        }
    }

    /**
    * Retorna as mensagens do sistema
    *
    * @access public
    * @uses Bootstrap::implode_r()
    * @return string HTML das mensagens geradas pelo sistema
    */
    public function getLog(){
        return !empty($this->log) ? implode_r(array('pieces'=>$this->log,'glue'=>'<br />')) : '';
    }

}//end class
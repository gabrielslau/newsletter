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
        $this->max_sent_per_hour   = 50;           // Máximo de emails enviados por vez (o limite da locaweb é 500, mas utilizamos a menos por garantia)
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
    function send(){
        $this->getNewslettersQueue();   // Newsletters na fila para enviar
        $this->getDestinatarios();      // Lista de emails nos grupos selecionados que faltam receber a newsletter
        
        if( $this->proceed ){

            if($this->total_destinatarios_in_queue > 0){
                $this->setLog("Pronto pra mandar a news #".$this->Newsletter['Newsletter']['id']);
               
                /**
                 * Manda o email para a lista de emails selecionados
                */

                App::uses('CakeEmail', 'Network/Email');
                App::uses('Validation', 'Utility');
                App::uses('File', 'Utility');

                $ModelEmail = ClassRegistry::init('Email');
                $validate   = new Validation();
                $ids_queue  = array();

                $fileNewsletterLog = new File('files'.DS.'tmp'.DS.'newsletter-'.$this->Newsletter['Newsletter']['id'].'.txt');
                if(!$fileNewsletterLog->exists()){
                    $fileNewsletterLog->create();
                }
                
                // Envia a Newsletter para cada usuário da lista
                foreach ($this->destinatarios as $destinatario):
                    if( $validate->email( $destinatario['email']) && $this->proceed ) {
                        $CakeEmail = new CakeEmail('smtp');
                        // die($destinatario['email']);exit();
                        $CakeEmail->template( 'newsletter', $this->Newsletter['Template']['file'] )
                        ->emailFormat('html')
                        // ->name(  )
                        ->to( $destinatario['email'] )
                        ->subject( $this->Newsletter['Newsletter']['subject'] )
                        ->viewVars(
                            array(
                                'message' => $this->Newsletter['Newsletter']['emailbody'],
                                'unsubscribe_id' => $destinatario['email'],
                                'NewslettersId' => $this->Newsletter['Newsletter']['id'],
                                'show_full_html'=>false
                            )
                        );

                        $sendResult = $CakeEmail->send();

                        if(!$sendResult){
                            $this->sended = false;
                            $this->setLog("Não foi possível enviar a Newsletter #".$this->Newsletter['Newsletter']['id']." para o email ".$destinatario['email']);
                        }else{
                            $ids_queue[] = $destinatario['id']; // guarda o ID do usuáiro que recebeu o email, para eliminar da lista
                            
                            $fileNewsletterLog->append($destinatario['id'].';'); //Adiciona o ID do email enviado no log da newsletter

                            /*$ModelEmail->recursive = 0;
                            $ModelEmail->id = $destinatario['id'];
                            if(!$ModelEmail->saveField( 'status' , "'0'")){
                                $this->proceed = false;
                                $this->setLog("Não foi possível desabilitar o email ".$destinatario['email']. ' no envio da newsletter #'.$this->Newsletter['Newsletter']['id']);
                            }*/

                            $this->setLog("Email enviado com sucesso para ".$destinatario['email']);
                        }// end CakeMail->send()

                        // sleep(2); // Aguarda 5 segundos antes de enviar o próximo email ( BUG DO SMTP )
                    }else{
                        $this->setLog("O Email ".$destinatario['email']." não é válido");
                    }
                endforeach;

                $fileNewsletterLog->close();
                // Limpa os emails da fila
                // $this->disableEmailQueue($ids_queue);

                // A newsletter foi "teoricamente" enviada para os emails da lista, então cria/atualiza um Log de registro

                $sent = !empty($this->Newsletter['Log']['sent']) ? $this->Newsletter['Log']['sent'] : 0; //Enviadas até o momento
                $sent += count($ids_queue); // Atualiza o contador com o total de emails enviados na última remessa

                $ModelLog = ClassRegistry::init('Log');
                if( empty($this->Newsletter['Log']['id']) ){
                    $ModelLog->create();
                    $ModelLog->set('start_sending', date('Y-m-d H:i:s'));
                }else{
                    $ModelLog->id = $this->Newsletter['Log']['id'];
                    $sent         = $this->Newsletter['Log']['sent'];
                }

                $ModelLog->set('newsletter_id', $this->Newsletter['Newsletter']['id']);
                $ModelLog->set('sent', $sent); // Contador de newsletters enviadas
                $ModelLog->save();


                

                $this->setLog("Newsletter # ".$this->Newsletter['Newsletter']['id'].' enviada em '.date('Y-m-d H:i:s'));
            }//end total_destinatarios_in_queue > 0
            else{
                // Se não tiver emails para o envio da news, impede que as outras operações sejam realizadas
                // e desativa a newsletter da fila de envio
                $this->proceed = false;
                $this->disableNewsletterQueue();
                $this->refreshEmailQueue();
            }
        }else{
            $this->setLog("Não foi possível enviar a Newsletter");
            return false;
        }
    }// end function send()

/**
* Seleciona alguma newsletter na fila de envio para o dia atual ou pendentes dos dias anteriores
* 
* Seleciona apenas a primeira newsletter para evitar ultrapassar o limite de envio de emails do SMTP
* 
* @access public
* @return void
*/
    function getNewslettersQueue(){
        $ModelNewsletter       = ClassRegistry::init('Newsletter');


        $ModelNewsletter->Behaviors->attach('Containable');
        $this->Newsletter = $ModelNewsletter->find('first', array(
            'conditions'=>array(
                "`Newsletter`.`status` = '1'",
                "CAST(Newsletter.date_send AS DATE) <= CAST( NOW() AS DATE )"
            ),
            'order'=>'Newsletter.date_send ASC',
            'contain'=>array(
                'User','Log','Template'/*,
                'Group'=>array(
                    'Email'=>array(
                        'conditions'=>array("Email.status = '1'"),
                        'limit'=>$this->max_sent_per_hour
                    )
                ),
                'Email'=>array(
                    'conditions'=>array("Email.status = '1'"),
                    'limit'=>$this->max_sent_per_hour
                )*/
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
    function getDestinatarios(){
        // Só realiza a operação se tiver alguma newsletter na fila de envio
        if($this->proceed){
            // Retorna os emails associados a newsletter
            
            $ModelNewsletter = ClassRegistry::init('Newsletter');
            $conditionsForEmails = array("Email.status = '1'");

            // Verifica se há algum arquivo de registro de emails que receberam a newsletter e adiciona no filtro
            // Esse arquivo guarda os IDs dos emails que receberam a newsletter, reparados por VIRGULA (,)
            App::uses('File', 'Utility');
            $fileNewsletterLog = new File('files'.DS.'tmp'.DS.'newsletter-'.$this->Newsletter['Newsletter']['id'].'.txt');

            if($fileNewsletterLog->exists()){
                $content = $fileNewsletterLog->read();
                if($content){
                    $content = explode(';', $content);
                    $content = array_filter($content, "checkEmpty"); //Limpa os campos vazios do array
                    
                    $conditionsForEmails = array(
                        "Email.status = '1'",
                        'NOT'=>array(
                            'Email.id'=>$content
                        )
                    );
                }
            }

            // Lista de Emails selecionados individualmente para a newsletter
            $EmailsInNews = $ModelNewsletter->Email->find('all', array(
                'joins' => array( 
                    array( 
                        'table' => 'newsletters_emails', 
                        'alias' => 'NewslettersEmail', 
                        'type' => 'inner',  
                        'conditions'=> array(
                            'NewslettersEmail.email_id = Email.id',
                            "NewslettersEmail.newsletter_id = '".$this->Newsletter['Newsletter']['id']."'"
                        ) 
                    ),
                ),
                'conditions'=>$conditionsForEmails,
                'limit'=>$this->max_sent_per_hour
            ));

            // Lista de Emails dos Grupos selecionados para a newsletter
            $ModelNewsletter->Group->Behaviors->attach('Containable');
            $GroupsInNews = $ModelNewsletter->Group->find('all',array(
                'joins' => array( 
                    array( 
                        'table' => 'newsletters_groups', 
                        'alias' => 'NewslettersGroup', 
                        'type' => 'inner',  
                        'conditions'=> array(
                            'NewslettersGroup.group_id = Group.id',
                            "NewslettersGroup.newsletter_id = '".$this->Newsletter['Newsletter']['id']."'"
                        ) 
                    ),
                ),
                'contain'=>array(
                    'Email'=>array(
                        'conditions'=>$conditionsForEmails,
                        'limit'=>$this->max_sent_per_hour
                    )
                )
            ));


            // print_r($GroupsInNews);exit();


            /**
             * Procura os emails dos grupos e monta uma lista de emails únicos para enviar fazendo a filtragem de quantidade máxima de emails
            */

            $i = 0; //contador geral
            App::uses('Validation', 'Utility');
            $validate = new Validation();

            
            // armazena os emails na lista
            if(!empty($EmailsInNews)):
                // print_r($this->Newsletter);exit();
                foreach ($EmailsInNews as $email) {
                    if( !in_array_r($email['email'], $this->destinatarios) && $i <= $this->max_sent_per_hour && $validate->email($email['email'], true) ){

                        $this->destinatarios[$i]['email'] = $email['email'];
                        $this->destinatarios[$i]['nome']  = $email['nome'];
                        $this->destinatarios[$i]['id']    = $email['id'];
                        $i++;
                    }
                }
            endif;

            // Procura emails na lista de grupos
            if(!empty($GroupsInNews)):
                foreach ($GroupsInNews as $group) {
                    foreach ($group['Email'] as $email){
                        if( !in_array_r($email['email'], $this->destinatarios) && $i <= $this->max_sent_per_hour && $validate->email($email['email'], true) ){

                            $this->destinatarios[$i]['email'] = $email['email'];
                            $this->destinatarios[$i]['nome']  = $email['nome'];
                            $this->destinatarios[$i]['id']    = $email['id'];
                            $i++;
                        }
                    }
                }
            endif;

            $this->total_destinatarios_in_queue = count($this->destinatarios);
            $this->setLog('Total de destinatários disponíveis para envio da news: '.$this->total_destinatarios_in_queue);
            
            if( $this->total_destinatarios_in_queue == 0 ){
                // Se não tiver emails para o envio da news, impede que as outras operações sejam realizadas
                // e desativa a newsletter da fila de envio
                $this->proceed = false;
                $this->disableNewsletterQueue();
                $this->refreshEmailQueue();

            }

        }
    } //end getDestinatarios()

/**
* Desabilita a newsletter atual
* 
* @access private
* @return void
*/
    function disableNewsletterQueue(){
        $ModelNewsletter     = ClassRegistry::init('Newsletter');
        $ModelNewsletter->id = $this->Newsletter['Newsletter']['id'];

        if (!$ModelNewsletter->saveField('status',0)) {
            $this->setLog( __('A Newsletter # %s não pôde ser desativada. ', $this->Newsletter['Newsletter']['id'] ));
        }else{
            $this->setLog( __('A newsletter # %s foi enviada para todos os remetentes e foi desabilitada. ', $this->Newsletter['Newsletter']['id'] ));

            // Atualiza o log com a data em que terminou de enviar a newsletter
            if( !empty($this->Newsletter['Log']['id']) ){
                $ModelNewsletter->Log->id = $this->Newsletter['Log']['id'];
                $ModelNewsletter->Log->set('end_sending',date('Y-m-d H:i:s'));
                $ModelNewsletter->Log->save();
            }
        }
    } //end disableNewsletterQueue()

/**
* Desativa alguns emails da lista de emails deixando-os temporariamente indisponíveis para novo envio
* 
* @access private
* @return void
*/
    function disableEmailQueue($ids_queue=array()){
        if(!empty($ids_queue)){
            $ModelEmail     = ClassRegistry::init('Email');
            
            // Limpa os emails da fila
            $ModelEmail = ClassRegistry::init('Email');
            if (!$ModelEmail->updateAll( array("Email.status"=>"'0'"), array('Email.id'=>$ids_queue) )) {
                $this->setLog("Não foi possível limpar a lista de emails que receberam a última newsletter");
            }else{
                $this->setLog( count($ids_queue) ." emails foram excluídos da fila de espera");
            }
        }
    } //end refreshEmailQueue()

/**
* Reseta a lista de emails deixando-as disponíveis para novo envio
* 
* @access private
* @return void
*/
    function refreshEmailQueue(){
        $ModelEmail     = ClassRegistry::init('Email');
        // $ModelEmail->id = $this->Newsletter['Newsletter']['id'];

        if (!$ModelEmail->updateAll( array('status'=>true) )) {
            $this->setLog( 'Não foi possível resetar a lista de email');
        }else{
            $this->setLog('Lista de emails está disponível para recebimento de nova newsletter.');
        }
    } //end refreshEmailQueue()


/**
* Guarda as mensagens de log do sistema
*
* @access private
* @uses CakeLog::write()
* @return void
*/
    function setLog($mensagem=null){
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
    function getLog(){
        return !empty($this->log) ? implode_r(array('pieces'=>$this->log,'glue'=>'<br />')) : '';
    }

}//end class
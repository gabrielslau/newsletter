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
    var $total_destinatarios;

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
    var $total_queue;

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
    * Objeto do Model Newslettersemail
    *
    * @access private
    * @var object
    */
    var $Newslettersemail;

    /**
    * Objeto do Model Newsletterslog
    *
    * @access private
    * @var object
    */
    var $Newsletterslog;
    /**
    * Objeto do Model Newslettersqueues
    *
    * @access private
    * @var object
    */
    var $Newslettersqueues;


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
        $this->max_sent_per_hour = 400;            // Máximo de emails enviados por vez (o limite da locaweb é 500, mas utilizamos a menos por garantia)
        //$this->sec             = 10;             // Tempo entre o envio de um pacote e outro (em segundos)
        //$this->limitMail       = 500;            // Limite de emails por hora
        //$this->secLimitMail    = 2400;           // Tempo de pausa por hora (40 minutos = 2400 segundos)
        
        $this->total_queue       = 0;              // Número de newsletters para enviar
        $this->Newslettersqueue  = array(); // Lista de newsletters agendadas e seus Models relacionados
        $this->destinatarios     = array(); // Lista de emails de quem falta receber a newsletter




        $this->id_enviada        = null;           // ID do log de envios de newsletters do dia

        $this->total_enviados         = 0;              // Enviados até o momento
        $this->total_emails       = 0;              // Remetentes cadastrados no sistema
        $this->total_destinatarios = 0;              // Remetentes que faltam receber o informativo
        
        $this->subject                = '';             // Assunto do email
        $this->email_body             = '';             // Corpo da news
        
        $this->proceed                = true;           // Se pode continuar com as operações ou não
        $this->sended                 = true;           // Se foi enviada ou não
        $this->data_atual             = date('d/m/Y');  // Data de início da operação
        $this->log                    = array();        // Registro de mensagens do sistema
        $this->error                  = array();        // Registro de mensagens de erro do sistema
        
        // Models a utilizar
        $this->Newslettersemail       = ClassRegistry::init('Newslettersemail');
        $this->Newslettersgroup       = ClassRegistry::init('Newslettersgroup');
        // $this->Newslettersqueue       = ClassRegistry::init('Newslettersqueue');
        $this->Newsletterslog         = ClassRegistry::init('Newsletterslog');
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
        $this->getNewslettersQueue();  // Newsletters na fila para enviar
        $this->getDestinatarios();      // Lista de emails nos grupos selecionados que faltam receber a newsletter
        


        $this->getTotalEnviadasHoje();

        if(!in_array($this->tipo_news,$this->tipos_permitidos)){
            $this->proceed = $this->sended = false;
            $this->log[] = $this->error[] = "O tipo de newsletter selecionada está inválida";
            CakeLog::write("debug", "O tipo de newsletter selecionada está inválida");
        }

        if($this->total_queue > 0 && $this->total_emails > 0 && $this->total_enviados < $this->total_emails && $this->proceed){
            $this->log[] = 'Pronto pra mandar a news';
            $this->log[] = 'ENVIADOS: '.$this->total_enviados;
            $this->log[] = 'REMENTENTES: '.$this->total_emails;
            CakeLog::write("debug", 'Pronto pra mandar a news');
            CakeLog::write("debug", 'ENVIADOS: '.$this->total_enviados);
            CakeLog::write("debug", 'REMENTENTES: '.$this->total_emails);

            if($this->total_destinatarios > 0){
                $remetentes = $this->Newslettersemail->find('all',array('conditions'=>array("`status` = '0'"),'limit'=>$this->max_sent_per_hour));
                // $Newslettersqueue = $this->Newslettersqueue->find('first',array('conditions'=>array('`tipo`'=>$this->tipo_news,"`status` = '0'")));

            /**
             * Verifica se há alguma newsletter agendada para hoje
            */
                // TODO: Fazer a verificação a nível de hora e minuto
                $this->Newslettersqueue->Behaviors->attach('Containable');
                $Newslettersqueue = $this->Newslettersqueue->find('first', array(
                    'conditions'=>array(
                        "`status` = '0'",
                        "CAST(Newslettersqueue.data_envio AS DATE) = CAST( NOW() AS DATE )"
                        // "DATE_FORMAT(`Newslettersqueue`.`data_envio`,'%d/%m/%Y') = DATE_FORMAT(NOW(),'%d/%m/%Y')"
                    ),
                    'contain'=>array(
                        'Newslettersuser',
                        'Newslettersgroup'=>array(
                            'Newslettersemail'=>array(
                                'conditions'=>array("Newslettersemail.status = '0'"),
                                'limit'=>$this->max_sent_per_hour
                            )
                        )
                    )
                ));
                // $log = $this->Newslettersqueue->getDataSource()->getLog();debug($log);exit;
                // print_r($Newslettersqueue);exit();

                if( count($Newslettersqueue['Newslettersgroup']) == 0 ){
                    $this->log[] = $this->error[] = 'Total de grupos insuficientes para o envio da news';
                    CakeLog::write("debug", 'Total de grupos insuficientes para o envio da news');
                    return false;
                }


                /**
                 * Procura os emails dos grupos e monta uma lista de emails únicos para enviar
                */
                $lista_de_destinatarios = array();
                $remetentesenviados = 0; // Total de remetentes que receberam os emails
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

                /**
                 * Manda o email para a lista de emails selecionados
                */

                App::uses('CakeEmail', 'Network/Email');
                foreach ($lista_de_destinatarios as $email) {
                    $email['email'] = trim($email['email']);

                    if( validarEmail( $email['email'] ) ):
                        $CakeEmail = new CakeEmail('smtp');
                        $CakeEmail->to( $email['email'] )
                    // debug($CakeEmail);
                    // exit();
                        // ->from(array(MAIL_REMETENTE => MAIL_REMETENTENAME))
                        ->template('newsletter', 'newsletter_'.$Newslettersqueue['Newslettersuser']['username'])
                        ->emailFormat('html')
                        ->subject( $Newslettersqueue['Newslettersqueue']['subject'] )
                        ->viewVars(
                            array(
                                'message' => $Newslettersqueue['Newslettersqueue']['emailbody'],
                                'NewslettersqueueId'=>$Newslettersqueue['Newslettersqueue']['id'],
                                'send_to'=>$email['email'],
                                'show_full_html'=>false
                            )
                        );

                        if(!$CakeEmail->send()){
                            $this->sended = false;
                            $this->log[] = $this->error[] = 'Houve um problema no envio da mensagem. Tente enviar novamente.';
                            CakeLog::write("debug", "Email de ".$Newslettersqueue['Newslettersuser']['nome']." enviado para ".$email['email']." na Newslettersqueue # ".$Newslettersqueue['Newslettersqueue']['id']." falhou.");
                        }else{
                            // A newsletter foi enviada, então atualiza o ID do email que recebeu
                            $remetentesenviados++;

                            $this->Newslettersemail->id = $email['id'];
                            $this->Newslettersemail->saveField('status',true);



                        }
                    endif;
                }//end foreach envio de emails


                if($remetentesenviados >0){
                    $this->id_newsagendada = $Newslettersqueue['Newslettersqueue']['id'];
                    $this->upCountNews($this->id_newsagendada,($this->total_enviados+$remetentesenviados));
                }
            }//end total_destinatarios > 0
        }

        //Reseta os remetentes após enviar tudo e marca a newsletter agendada como enviada
        if($this->total_enviados >= $this->total_emails){
            $this->reset();
            $this->disabeNews($this->id_newsagendada);
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
        $ModelNewslettersqueue       = ClassRegistry::init('Newslettersqueue');

        $ModelNewslettersqueue->Behaviors->attach('Containable');
        $this->Newslettersqueue = $ModelNewslettersqueue->find('first', array(
            'conditions'=>array(
                "`status` = '0'",
                "CAST(Newslettersqueue.data_envio AS DATE) = CAST( NOW() AS DATE )"
            ),
            'order'=>'Newslettersqueue.data_envio ASC',
            'contain'=>array(
                'Newslettersuser',
                'Newslettersgroup'=>array(
                    'Newslettersemail'=>array(
                        'conditions'=>array("Newslettersemail.status = '0'")/*,
                        'limit'=>$this->max_sent_per_hour*/
                        // A limitação do número de emails é feita na hora do envio
                    )
                )
            )
        ));


        $this->total_queue = count($this->Newslettersqueue);
        $this->setLog("Total de newsletters na fila de envio em ($this->data_atual): $this->total_queue");

        // Se não tiver newsletter programada para envio, impede que as outras operações sejam realizadas
        if($this->total_queue == 0) $this->proceed = false; 
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
            foreach ($this->Newslettersqueue['Newslettersgroup'] as $group) {
                foreach ($group['Newslettersemail'] as $email) {
                    if( !in_array_r($email['email'], $this->destinatarios) )
                        $this->destinatarios[] = array(
                            'id' => $email['id'],
                            'nome' => $email['nome'],
                            'email' => $email['email']
                        );
                }
            }

            $this->total_destinatarios = count($this->destinatarios);
            $this->setLog('Total de destinatários disponíveis para envio da news: '.$this->total_destinatarios);
            // die((string)$this->total_destinatarios);

            // Se não tiver emails para o envio da news, impede que as outras operações sejam realizadas
            // e reseta os emails associados a esta newsletter
            // e desativa a newsletter
            if($this->total_destinatarios == 0) {
                $this->proceed = false;

                $this->disableNewsletterQueue();
            }
            unset($this->Newslettersqueue['Newslettersgroup']); //Limpa o array de emails e grupos para liberar memoria
        }
    } //end getDestinatarios()

    /**
    * Desabilita a newsletter atual, e atualiza
    * 
    * @access private
    * @return void
    */
     /*private function resetDestinatarios(){
        $destinatarios_ids = array(); // Monta uma lista com os ids a desativar
        foreach ($this->destinatarios as $destinatario) {
            $destinatarios_ids[] = $destinatario['id'];
        }
     }*/

    /**
    * Reseta os endereços de emails para torná-los disponíveis para novo envio ( por outra newsletter, é claro ¬¬ )
    * 
    * @access private
    * @return void
    */
     private function resetDestinatarios(){
        $destinatarios_ids = array(); // Monta uma lista com os ids a desativar
        foreach ($this->destinatarios as $destinatario) {
            $destinatarios_ids[] = $destinatario['id'];
        }
     }










    /**
    * Reseta os status dos remetentes
    * 
    * @access private
    */
    function reset(){
        $this->Newslettersemail->updateAll(array("`status` = '0'"));
        $this->log[] = 'Todos os remetentes estão disponíveis para novo envio de newsletter.';
        CakeLog::write("debug", 'Todos os remetentes estão disponíveis para novo envio de newsletter.');
    }

    /**
    * Marca a newsletter como enviada
    * 
    * @access private
    */
    function disabeNews($id){
        // $this->Newsletteragendadas->update(array('status'=>"'0'"));
        $this->Newslettersqueue->id = $id;
        $this->Newslettersqueue->saveField('status','0');
        $this->log[] = "A newsletter #$id foi enviada para todos os remetentes e foi desabilitada.";
        CakeLog::write("debug", "A newsletter #$id foi enviada para todos os remetentes e foi desabilitada.");
    }

    /**
    * Atualiza o contador da quantidade de newsletters enviadas
    * 
    * @access private
    */
    // update `newslettersusuarios` set `status`='0'
    function upCountNews($id=null,$count=0){
        if(!empty($id) && !empty($count)){

            if(!$this->total_enviados>0){
                $this->Newsletterslog->create();
                $this->Newsletterslog->set('newslettersqueue_id', $id);
                $this->Newsletterslog->set('numero_enviados', $count);
                $this->Newsletterslog->set('sended', date('Y-m-d H:i:s'));
                $this->Newsletterslog->save();
                // die('ENVIDAOS >! 0');
            }else{
                $this->Newsletterslog->id = $this->id_enviada;
                $this->Newsletterslog->set('newslettersqueue_id', $id);
                $this->Newsletterslog->saveField('numero_enviados',$count);
                // die('ENVIADOS > 0');
            }
            
            //$this->Newslettersqueue->save();
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
    function getTotalEnviadasHoje(){
        //$tablename = $Newsletterslog->table;
        $options['fields'] = array('id',"numero_enviados",'newslettersqueue_id');
        $options['recursive'] = -1;
        $options['conditions'] = array(
            // "DATE_FORMAT(`sended`,'%d/%m/%Y') = DATE_FORMAT(NOW(),'%d/%m/%Y')"
            "CAST(sended AS DATE) = CAST( NOW() AS DATE )"
        );
        //$this->total_enviados = $Newsletterslog->find('count',$options);
        $enviadas = $this->Newsletterslog->find('first',$options);
        // $log = $this->Newsletterslog->getDataSource()->getLog();debug($log);exit;
        $this->total_enviados = ($enviadas['Newsletterslog']['numero_enviados'] > 0 ? $enviadas['Newsletterslog']['numero_enviados'] : 0);
        $this->id_enviada = $enviadas['Newsletterslog']['id'];
        $this->id_newsagendada = $enviadas['Newsletterslog']['newslettersqueue_id'];
        $this->log[] = '<b>Total de newsletters enviadas hoje:</b> '.$this->total_enviados;
        CakeLog::write("debug", "Total de newsletters enviadas hoje: ".$this->total_enviados);
    }

    /**
    * Pega o total de remetentes cadastrados no sistema
    * 
    * @access private
    *
    * @return integer : Total de remetentes
    */
    function getTotalRemetentes(){
        $this->total_emails = $this->Newslettersemail->find('count');
        $this->log[] = '<b>Total de remetentes cadastrados no sistema:</b> '.$this->total_emails;
        CakeLog::write("debug", 'Total de remetentes cadastrados no sistema: '.$this->total_emails);
        // die((string)$this->total_emails);
    }

    /**
    * Pega o total de remetentes que ainda não receberam a newsletter
    * 
    * @access private
    *
    * @return integer : Total de enviados
    */
    function getTotalRemetentesNaFila(){
        $options = array(
            'conditions'=>array("`status` = '0'")
        );
        $this->total_destinatarios = $this->Newslettersemail->find('count',$options);
        // $log = $this->Newslettersemail->getDataSource()->getLog();debug($log);exit;
        $this->log[] = '<b>Total de remetentes disponiveis para envio da news:</b> '.$this->total_destinatarios;
        CakeLog::write("debug", 'Total de remetentes disponiveis para envio da news: '.$this->total_destinatarios);
        // die((string)$this->total_destinatarios);
    }




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

    /**
    * Retorna as mensagens de erro do sistema
    *
    * @access public
    * @uses Bootstrap::implode_r()
    * @return string HTML das mensagens de erro geradas pelo sistema
    */
    function getError(){
        return !empty($this->error) ? implode_r(array('pieces'=>$this->error,'glue'=>'<br />')) : '';
    }

    /**
     * Retorna o nome do mês por extenso
     * 
     * @access public
     *
     * @param string $m: O numero do mês
     * @return string
     */
    function getMesExt($m){
        switch($m){
            case "01": return "Janeiro"; break;
            case "02": return "Fevereiro"; break;
            case "03": return "Mar&ccedil;o"; break;
            case "04": return "Abril"; break;
            case "05": return "Maio"; break;
            case "06": return "Junho"; break;
            case "07": return "Julho"; break;
            case "08": return "Agosto"; break;
            case "09": return "Setembro"; break;
            case "10": return "Outubro"; break;
            case "11": return "Novembro"; break;
            case "12": return "Dezembro"; break;
        }
    }
    
    /**
     * Retorna a data de envio da newsletter
     * 
     * @uses Newsletter::getMesExt()
     * @access public
     *
     * @return string $dataNova
     */
    function dataNews() {
        $data = date("d/m/Y");
        list($dia, $mes, $ano) = explode('/', $data);
        return ($dia-1) ." de ". $this->getMesExt($mes) ." / ". $ano;
    }
}//end class
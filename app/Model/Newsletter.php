<?php
App::uses('AppModel', 'Model');
/**
 * Newsletter Model
 *
 */
class Newsletter extends AppModel {
	var $name = 'Newsletter';
	var $displayField = 'subject';
	public $actAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */	
	var $validateCadastroNews = array(
		'newslettersuser_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'O ID do usuário deve ser informado',
				//'required' => false,
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		

		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe o título da newsletter',
				'required' => true,
			),
		),

		'emailbody' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe o conteúdo da newsletter',
				'required' => true,
			),
		),


		'date_send' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe uma data para o envio da newsletter',
				'required' => true,
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	// As associações abaixo foram criadas com todas as chaves possíveis, então é possível remover as que não são necessárias

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id'
		)
	);

	var $hasMany = array(
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'newsletter_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Queue' => array(
			'className' => 'Queue',
			'foreignKey' => 'newsletter_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	var $hasAndBelongsToMany = array(
		'Email' => array(
			'className' => 'Email',
			'joinTable' => 'newsletters_emails',
			'foreignKey' => 'newsletter_id',
			'associationForeignKey' => 'email_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'newsletters_groups',
			'foreignKey' => 'newsletter_id',
			'associationForeignKey' => 'group_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

	/**
	 * Callbacks
	 *
	 * @var array
	 */
	function afterFind($results) {
		return array_to_utf8($results);
	}

	function beforeSave($options) {
		if (!empty($this->data)){
			// print_r($this->data);exit();
			// Corrige a codificacao dos dados
			$this->data['Newsletter'] = array_to_utf8($this->data['Newsletter'],true);

			if( isset($this->data['Newsletter']['date_send']) && !empty($this->data['Newsletter']['date_send']) ) {
				$date = new DateTime( str_replace('/', '-', trim($this->data['Newsletter']['date_send']))  );
				$this->data['Newsletter']['date_send'] =  $date->format('Y-m-d H:i:s');
			}
		}

		return true;
	}
}//end Model
?>
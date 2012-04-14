<?php
App::uses('AppModel', 'Model');
/**
 * Newslettersqueue Model
 *
 * @property Newsletterslog $Newsletterslog
 */
class Newslettersqueue extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'subject';
	public $actAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validateCadastroNews = array(
		'newslettersuser_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'O ID do usuário deve ser informado',
				//'required' => false,
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'to' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe o(s) grupo(s) para os quais será enviada a newsletter',
				'required' => true,
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


		'data_envio' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe uma data para o envio da newsletter',
				'required' => true,
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Newsletterslog' => array(
			'className' => 'Newsletterslog',
			'foreignKey' => 'newslettersqueue_id',
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


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Newslettersuser' => array(
			'className' => 'Newslettersuser',
			'foreignKey' => 'newslettersuser_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Newslettersgroup' => array(
			'className' => 'Newslettersgroup',
			'joinTable' => 'newslettersqueues_newslettersgroups',
			'foreignKey' => 'newslettersqueue_id',
			'associationForeignKey' => 'newslettersgroup_id'
			// 'unique' => true
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
			// $this->data = array_to_utf8($this->data);
			$this->data['Newslettersqueue'] = array_to_utf8($this->data['Newslettersqueue'],true);
		}
		return true;
	}

}//end Model
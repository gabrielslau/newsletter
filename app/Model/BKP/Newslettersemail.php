<?php
App::uses('AppModel', 'Model');
/**
 * Newslettersemail Model
 *
 * @property Newslettersgroup $Newslettersgroup
 */
class Newslettersemail extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'email';

	public $actsAs = array('HabtmCounterCache.HabtmCounterCache');

/**
 * Validation rules
 *
 * @var array
 */
	public $validateCadastroEmail = array(
		'newslettersgroup_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'nome' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe um nome',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe um email',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Insira um email válido',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	/*public $belongsTo = array(
		'Newslettersgroup' => array(
			'className' => 'Newslettersgroup',
			'foreignKey' => 'newslettersgroup_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/


	public $hasAndBelongsToMany = array(
		'Newslettersgroup' => array(
			'className' => 'Newslettersgroup',
			'joinTable' => 'newslettersemails_newslettersgroups',
			'foreignKey' => 'newslettersemail_id',
			'associationForeignKey' => 'newslettersgroup_id',
			'counterCache' => true
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
			$this->data['Newslettersemail'] = array_to_utf8($this->data['Newslettersemail'],true);
		}
		return true;
	}

}//end Model

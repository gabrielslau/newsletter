<?php
App::uses('AppModel', 'Model');
/**
 * Newslettersgroup Model
 *
 * @property Newslettersemail $Newslettersemail
 */
class Newslettersgroup extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nome';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'nome' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
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
	/*public $hasMany = array(
		'Newslettersemail' => array(
			'className' => 'Newslettersemail',
			'foreignKey' => 'newslettersgroup_id',
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
	);*/


	public $hasAndBelongsToMany = array(
		'Newslettersemail' => array(
			'className' => 'Newslettersemail',
			'joinTable' => 'newslettersemails_newslettersgroups',
			'foreignKey' => 'newslettersgroup_id',
			'associationForeignKey' => 'newslettersemail_id'
			// 'unique' => true
		),
		'Newslettersqueue' => array(
			'className' => 'Newslettersqueue',
			'joinTable' => 'newslettersqueues_newslettersgroups',
			'foreignKey' => 'newslettersgroup_id',
			'associationForeignKey' => 'newslettersqueue_id'
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
			$this->data['Newslettersgroup'] = array_to_utf8($this->data['Newslettersgroup'],true);
		}
		return true;
	}

}//end Model

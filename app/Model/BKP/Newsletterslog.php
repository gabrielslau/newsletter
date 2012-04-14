<?php
App::uses('AppModel', 'Model');
/**
 * Newsletterslog Model
 *
 * @property Newslettersqueue $Newslettersqueue
 */
class Newsletterslog extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'newslettersqueue_id';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'newslettersqueue_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'numero_enviados' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Newslettersqueue' => array(
			'className' => 'Newslettersqueue',
			'foreignKey' => 'newslettersqueue_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}//end Model

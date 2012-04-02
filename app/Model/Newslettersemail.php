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
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
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
		'Newslettersgroup' => array(
			'className' => 'Newslettersgroup',
			'foreignKey' => 'newslettersgroup_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

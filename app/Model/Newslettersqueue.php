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

}//end Model
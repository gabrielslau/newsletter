<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Group extends AppModel {
	var $displayField = 'nome';
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */	
	var $validateCadastro = array(
		'nome' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe um nome para o grupo',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Para a validação após esta regra
				//'on' => 'create', // Limitar a validação para as operações 'create' ou 'update'
			),
		),
	);
	// As associações abaixo foram criadas com todas as chaves possíveis, então é possível remover as que não são necessárias

	var $hasAndBelongsToMany = array(
		'Email' => array(
			'className' => 'Email',
			'joinTable' => 'groups_emails',
			'foreignKey' => 'group_id',
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
		'Newsletter' => array(
			'className' => 'Newsletter',
			'joinTable' => 'newsletters_groups',
			'foreignKey' => 'group_id',
			'associationForeignKey' => 'newsletter_id',
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
			// $this->data = array_to_utf8($this->data);
			$this->data['Group'] = array_to_utf8($this->data['Group'],true);
		}
		return true;
	}
}//end Model
?>
<?php
App::uses('AppModel', 'Model');
/**
 * Email Model
 *
 */
class Email extends AppModel {
	public $name = 'Email';
	public $displayField = 'email';
	public $actsAs = array('HabtmCounterCache.HabtmCounterCache');

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validateCadastroEmail = array(
		/*'Group.id' => array(
			'multiple' => array(
				'rule'     => array('multiple', array('min' => 1)),
				'required' => true,
				'message'  => 'Informe pelo menos um grupo'
			)
		),*/
		'nome' => array(
			'notempty' => array(
				'rule'         => array('notempty'),
				'message'      => 'Informe um nome',
				//'allowEmpty' => false,
				'required'     => true,
				//'last'       => false, // Stop validation after this rule
				//'on'         => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'notempty' => array(
				'rule'         => array('notempty'),
				'message'      => 'Informe um email',
				//'allowEmpty' => false,
				'required'     => true,
				//'last'       => false, // Stop validation after this rule
				//'on'         => 'create', // Limit validation to 'create' or 'update' operations
			),
			'email' => array(
				'rule'         => array('email',true),
				'message'      => 'Insira um email válido',
				//'allowEmpty' => false,
				//'required'   => false,
				//'last'       => false, // Stop validation after this rule
				//'on'         => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule'    => 'isUnique',
				'message' => 'Este email já está em uso'
			)
		),
	);
	// As associações abaixo foram criadas com todas as chaves possíveis, então é possível remover as que não são necessárias

	public $hasMany = array(
		'Queue' => array(
			'className'    => 'Queue',
			'foreignKey'   => 'email_id',
			'dependent'    => true,
			'conditions'   => '',
			'fields'       => '',
			'order'        => '',
			'limit'        => '',
			'offset'       => '',
			'exclusive'    => '',
			'finderQuery'  => '',
			'counterQuery' => ''
		)
	);


	public $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'groups_emails',
			'foreignKey' => 'email_id',
			'associationForeignKey' => 'group_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
			'counterCache' => true
		),
		'Newsletter' => array(
			'className' => 'Newsletter',
			'joinTable' => 'newsletters_emails',
			'foreignKey' => 'email_id',
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
			$this->data['Email'] = array_to_utf8($this->data['Email'],true);
		}
		return true;
	}

}//end Model
?>
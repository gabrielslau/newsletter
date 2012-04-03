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

/*
** Função para validação personalizada
** @link http://snook.ca/archives/cakephp/multiple_validation_sets_cakephp
*/
	function validates($options = array()) {
	    // copy the data over from a custom var, otherwise
	    $actionSet = 'validate' . Inflector::camelize(Router::getParam('action'));
	    if (isset($this->validationSet)) {
	        $temp = $this->validate;
	        $param = 'validate' . $this->validationSet;
	        $this->validate = $this->{$param};
	    } elseif (isset($this->{$actionSet})) {
	        $temp = $this->validate;
	        $param = $actionSet;
	        $this->validate = $this->{$param};
	    } 

	    $errors = $this->invalidFields($options);

	    // copy it back
	    if (isset($temp)) {
	        $this->validate = $temp;
	        unset($this->validationSet);
	    }
	    
	    if (is_array($errors)) {
	        return count($errors) === 0;
	    }
	    return $errors;
	}

}//end Model
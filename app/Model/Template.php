<?php
App::uses('AppModel', 'Model');
/**
 * Template Model
 *
 */
class Template extends AppModel {
	var $displayField = 'titulo';
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */	
	var $validateCadastro = array(
		'titulo' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe um título para o Template',
				'required' => true,
			),
		),
	);
	// As associações abaixo foram criadas com todas as chaves possíveis, então é possível remover as que não são necessárias

	var $hasMany = array(
		'Newsletter' => array(
			'className' => 'Newsletter',
			'foreignKey' => 'template_id'
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
			$this->data['Template'] = array_to_utf8($this->data['Template'],true);
		}
		return true;
	}
}//end Model
?>
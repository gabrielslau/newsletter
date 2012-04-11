<?php
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/Model/AppModel.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       Cake.Model
 */
class AppModel extends Model {

/**
* Função para validação personalizada
* @link http://snook.ca/archives/cakephp/multiple_validation_sets_cakephp
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

}//end AppModel
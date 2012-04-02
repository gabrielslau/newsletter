<?php 
/**
 * Componente que transforma as informações de uma query em json
 * @link http://www.michaelmafort.com.br/blog/index.php/2009/02/02/facilidades-para-sua-vida-parte-7-cakephp-json-encode/
 */

class JsonComponent extends Component {
 	var $name = 'Json';

 	function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
    }

	public function encode($data){
		$tmp_arr = array();
		if( is_array($data) ){
			foreach( $data as $key => $value ){
				if( is_array($value) ){
					$tmp_arr[] = '"'.$key.'":{' . $this->encode($value) . '}';
				}
				else{
					$tmp_arr[] = '"'.$key.'":"'.$value.'"';
				}
			}
		}
		else{
			return '"'.$data.'"';
		}
		return join(", ", $tmp_arr);
	}
 
}//end component
?>
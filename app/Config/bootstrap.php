<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as 
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, // [optional]
 * 		'mask' => 0666, // [optional] permission mask to use when creating cache files
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcache (http://memcached.org/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => true, // [optional] set this to false for non-persistent connections
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 */
set_time_limit(0);
ignore_user_abort(true);
Cache::config('default', array('engine' => 'File'));
Cache::config('long', array(
	'engine' => 'File',
	'duration'=> '+1 hour',
	'path' => CACHE
));

Cache::config('short', array(
	'engine' => 'File',
	'duration'=> '+10 minutes',
	'probability'=> 100,
	'path' => CACHE
));

/**
* Turn on MinifyHelper
*/
Configure::write('MinifyAsset', false);

define('NAMESITE', 'Professional Worldwide' );
// echo phpinfo();exit;

/*
** Dados de SMTP para envio de emails
*/	
	define('MAIL_PORTA', 587);
	define('MAIL_HOST', "smtp.profissionaldobrasil.com.br");
	define('MAIL_USER', "webmaster@profissionaldobrasil.com.br");
	define('MAIL_REMETENTE', "sac@profissionaldobrasil.com.br");
	define('MAIL_REMETENTENAME', NAMESITE);



/**
 * FUNÇÕES GERAIS
*/

function getFullBaseUrl(){
	// return (FULL_BASE_URL == 'http://127.0.0.1') ? FULL_BASE_URL.'/newsletter/' : FULL_BASE_URL.'/';
	return (FULL_BASE_URL == 'http://127.0.0.1') ? FULL_BASE_URL.'/newsletter/' : FULL_BASE_URL.'/';
}

/**
 * Função recursiva para saber se um valor está dentro de um array multidimensional
*/
function in_array_r($needle, $haystack) {
    foreach ($haystack as $item) {
        if ($item == $needle || (is_array($item) && in_array_r($needle, $item))) {
            return true;
        }
    }

    return false;
}

function checkEmpty($value){
    return !empty($value);
}

/*
** Função recursiva para transformar um array em uma string;
** @param array $pieces : The array of strings to implode
** @param string $before : Element to include at the beginning of the string
** @param string $after : Element to include at the end of the string
** @param string $glue : Element to include among the pieces of string
** 
** @return string
*/
function implode_r($params=array()){
	if(!is_array($params)) return false;
	else{
		if( !isset($params['pieces']) ) return false;
		else{
			$string = '';$i=0;$count = count($params['pieces']);
			foreach($params['pieces'] as $piece){
				if($i==0 && isset($params['before'])) $string .= $params['before'];
				
				if(is_array($piece)){
					$subparams = $params;
					$subparams['pieces'] = $piece;
					$string .= implode_r($subparams);
				}
				else $string .= $piece;

				if($i<$count-1 && isset($params['glue'])) $string .= $params['glue'];

				if($i==$count-1 && isset($params['after'])) $string .= $params['after'];
				$i++;
			}//end foreach
			return $string;
		}
	}
}

/**
* PHP CSS Browser Selector v0.0.1
* Bastian Allgeier (http://bastian-allgeier.de)
* http://bastian-allgeier.de/css_browser_selector
* License: http://creativecommons.org/licenses/by/2.5/
* Credits: This is a php port from Rafael Lima's original Javascript CSS Browser Selector: http://rafael.adm.br/css_browser_selector
*/
function css_browser_selector($ua=null) {
	// $ua = ($ua) ? strtolower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);		
	$ua = ($ua) ? strtolower($ua) : strtolower(env('HTTP_USER_AGENT'));

	$g = 'gecko';
	$w = 'webkit';
	$s = 'safari';
	$b = array();
	
	// browser
	if(!preg_match('/opera|webtv/i', $ua) && preg_match('/msie\s(\d)/', $ua, $array)) {
			$b[] = 'ie ie' . $array[1];
	}	else if(strstr($ua, 'firefox/2')) {
			$b[] = $g . ' ff2';		
	}	else if(strstr($ua, 'firefox/3.5')) {
			$b[] = $g . ' ff3 ff3_5';
	}	else if(strstr($ua, 'firefox/3')) {
			$b[] = $g . ' ff3';
	} else if(strstr($ua, 'gecko/')) {
			$b[] = $g;
	} else if(preg_match('/opera(\s|\/)(\d+)/', $ua, $array)) {
			$b[] = 'opera opera' . $array[2];
	} else if(strstr($ua, 'konqueror')) {
			$b[] = 'konqueror';
	} else if(strstr($ua, 'chrome')) {
			$b[] = $w . ' ' . $s . ' chrome';
	} else if(strstr($ua, 'iron')) {
			$b[] = $w . ' ' . $s . ' iron';
	} else if(strstr($ua, 'applewebkit/')) {
			$b[] = (preg_match('/version\/(\d+)/i', $ua, $array)) ? $w . ' ' . $s . ' ' . $s . $array[1] : $w . ' ' . $s;
	} else if(strstr($ua, 'mozilla/')) {
			$b[] = $g;
	}

	// platform				
	if(strstr($ua, 'j2me')) {
			$b[] = 'mobile';
	} else if(strstr($ua, 'iphone')) {
			$b[] = 'iphone';		
	} else if(strstr($ua, 'ipod')) {
			$b[] = 'ipod';		
	} else if(strstr($ua, 'mac')) {
			$b[] = 'mac';		
	} else if(strstr($ua, 'darwin')) {
			$b[] = 'mac';		
	} else if(strstr($ua, 'webtv')) {
			$b[] = 'webtv';		
	} else if(strstr($ua, 'win')) {
			$b[] = 'win';		
	} else if(strstr($ua, 'freebsd')) {
			$b[] = 'freebsd';		
	} else if(strstr($ua, 'x11') || strstr($ua, 'linux')) {
			$b[] = 'linux';		
	}

	return join(' ', $b);
}

/*
** Função recursiva para passar cada valor de um array para codificação UTF-8
*/
function array_to_utf8($array = array(), $decode = false) {
	$newarray = array();
    if(!empty($array)){
	    foreach ($array as $k=>$item) {
	        if($decode){
	        	$newarray[utf8_decode($k)] = is_array($item) ? array_to_utf8($item, $decode) : utf8_decode($item);
	        }else{
	        	$newarray[utf8_encode($k)] = is_array($item) ? array_to_utf8($item, $decode) : utf8_encode($item);
	        }
	    }
	}
    return $newarray;
}

/**
* Limita a quantidade de palavras
* @author    Gabriel (Okatsura) Lau + "Sujeito desconhecido"
* @param integer $str: String a ser analizada
* @param boolean $limite : Tamanho máximo de palavras permitidas
*
* @return string : A string reduzida, caso tenha passado do limite
*/
function limit_words($str, $limite){
	$words = $pos = 0; 
	$newStr = ''; 
	$str = eregi_replace(" +", " ", $str);
	$array = explode(" ", $str);
	
	for($i=0;$i < count($array);$i++){
		if (eregi("[0-9A-Za-zÀ-ÖØ-öø-ÿ]", $array[$i]))	 $words++;

		$newStr .= ($words <= $limite) ? ' '.$array[$i] : '';
	
	}//end for
	return $newStr;
}//end function limit_words()

/* Funçao que retorna o nome do mes por extenso*/
function getMesAbr($m){
	switch($m){
		case "01": return "Jan"; break;
		case "02": return "Fev"; break;
		case "03": return "Mar"; break;
		case "04": return "Abr"; break;
		case "05": return "Mai"; break;
		case "06": return "Jun"; break;
		case "07": return "Jul"; break;
		case "08": return "Ago"; break;
		case "09": return "Set"; break;
		case "10": return "Out"; break;
		case "11": return "Nov"; break;
		case "12": return "Dez"; break;
	}
	return $m;
}

/**
* Retorna a data no formato timestamp Unix
* @param string $data : Recebe a data no formato date('Y-m-d H:i:s') = (Ano-Mes-Dia Hora:Minuto:Segundo)
*
* @return string
*/
function getTimestamp($data = null){
	$data = !is_null($data) ? $data : date('Y-m-d H:i:s');
	
	$ano = substr($data, 0, 4);
	$mes = substr($data, 5, 2);
	$dia = substr($data, 8, 2);
	
	$hora = substr($data, 11,2);
	$min = substr($data, 14,2);
	$sec = substr($data, 17,2);
	
	return mktime($hora,$min,$sec,$mes,$dia,$ano); //mktime(hora, minuto, segundos, mes, dia, ano)
}// end getTimestamp()

/**
* Funções para retorno de um trecho específico de uma data
* @param string $data : Recebe a data no formato timestamp
*
* @return string
*/
function getDay($data){ return date('d',getTimestamp($data)); } //Retorna o dia de uma data
function getMonth($data){return date('m',getTimestamp($data));} //Retorna o mês de uma data
function getYear($data){$ano = substr($data, 0, 4);return ($ano < 0) ? '??' : $ano;} //Retorna o ano de uma data
function getHour($data){$hora = substr($data, 11,2);return ($hora < 0 || $hora >= 24) ? '??' : $hora;} //Retorna a hora de uma data
function getMinute($data){$min = substr($data, 14,2);return ($min < 0 || $min >= 60) ? '??' : $min;} //Retorna o minuto de uma data
function getSecond($data){$sec = substr($data, 17,2);return ($sec < 0 || $sec >= 60) ? '??' : $sec;} //Retorna o segundo de uma data

//Referencia: http://www.phpsnippets.info/display-dates-as-time-ago
	/*
	** Função para comparação de datas futuras e passadas
	* @param integer $timestamp : Recebe a data no formato timestamp
	* @param integer $deep : Nivel de profundidade do tempo. Ex.: anos - meses - semanas - dias - minutos - segundos
	*
	* @referencia: http://www.php.net/manual/en/function.time.php
	* @return string
	*/
	function getTimeAgo($timestamp = null, $deep = 2){

		$timestamp = (($timestamp === null) ? (time()) : ($timestamp));
		$timestamp = ((is_int($timestamp)) ? ($timestamp) : (strtotime($timestamp)));
		$hoje = time();
		$diff    = abs($hoje - $timestamp);
		$date_string = '';
		
		if($diff == 0) $date_string = ' agora';
		else{
			$suffix  = ($hoje > $timestamp) ? ' atrás' : '';
			$preffix = ($hoje < $timestamp) ? 'daqui a ' : 'a ';
			
			$periodos = array
			(
				"ano"   	=> 29030400, // seconds in a year   (12 months)
				"mês"  		=> 2419200,  // seconds in a month  (4 weeks)
				"semana"   	=> 604800,   // seconds in a week   (7 days)
				"dia"    	=> 86400,    // seconds in a day    (24 hours)
				"hora"   	=> 3600,     // seconds in an hour  (60 minutes)
				"minuto" 	=> 60,       // seconds in a minute (60 seconds)
				"segundo" 	=> 1         // 1 second
			);

			foreach($periodos as $periodo => $tempo){
				if($diff >= $tempo){
					$time = intval($diff / $tempo);
					$s = ($time == 1) ? '' : ( ($periodo == 'mês') ? 'es' : 's');
					$and = ($tempo != 1 && $deep > 2) ? ", " : (($tempo != 1 && $deep > 1) ? " e " : "");
					$date_string .= $time.' '.$periodo.$s.$and;
					$diff %= $tempo;
					$deep--;
				}
				if($deep == 0) break;
			}//end foreach
			$date_string = $preffix.$date_string.$suffix;
		}//end else
		
		return $date_string;
	}//end function getTimeAgo($timestamp)

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Plugin' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'Model' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'View' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'Controller' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'Model/Datasource' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'Model/Behavior' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'Controller/Component' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'View/Helper' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'Vendor' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'Console/Command' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'Locale' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */
// CakePlugin::loadAll();
CakePlugin::load('CakePtbr');
require CakePlugin::path('CakePtbr') . DS . 'Config' . DS . 'traducao_core.php';

CakePlugin::load('Cuploadify');
// CakePlugin::load('AssetCompress');
CakePlugin::load('HabtmCounterCache');
// CakePlugin::load('DebugKit');
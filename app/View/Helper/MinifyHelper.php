<?php
// App::uses('AppHelper', 'Helper');
App::uses('AppHelper', 'View/Helper');
/**
* This file contains the minifer helper
*
* PHP version 5
*
* @category Helper
* @author Ketan Shah <ketan.shah@innovatechnologies.in>
*/

/**
* Minify helper.
*
* This helper acts as a wrapper for 3-rd party minify
*
* @category Helper
* @author Ketan Shah <ketan.shah@innovatechnologies.in>
*/
class MinifyHelper extends AppHelper{

/**
* Array containing helper names.
*
* @var array
* @since 1.0.0.0
*/
    public $helpers = array('Js','Html');

                   
/**
* Variable to hold included javascript files.
*
* @var array
* @since 1.0.0.0
*/
    public $jsFiles = array();
    
    
/**
* Variable to hold included css files.
*
* @var array
* @since 1.0.0.0
*/
    public $cssFiles = array();


    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
        if (!empty($settings['configFile'])) {
            $this->loadConfig($settings['configFile']);
        }
    }
   
/**
* Minifies javascript files if MinifyAsset is set to true.
*
* @param array $assets Array containing relative file names
* @param boolean $inline Whether the script should be added inline or in head section of html
* @param boolean $prepend Whether to prepend the script to buffer or append
*
* @return void
*/
    public function js($assets, $inline = true, $prepend = false)
    {
        // If assets is not array then cast it
        if (!is_array($assets)) {
            $assets = (array)$assets;
        }
        
        if (Configure::read('MinifyAsset')) {
            if ($inline) {
                $src = $this->_path($assets, 'js');
                echo sprintf("<script type='text/javascript' src='%s'></script>", $src);
            } else {
                // Whether we need to prepend the script to buffer or append
                if ($prepend) {
                    $this->__prependScripts($assets, 'jsFiles');
                } else {
                    $this->jsFiles = array_merge($this->jsFiles, $assets);
                }
            }
        } else {
            // If we are not minifying and prepend is true then it means we need to display it inline
            if ($prepend) {
                $inline = true;
            }
            
            echo $this->Html->script($assets, $inline);
            // echo $this->Javascript->link($assets, $inline);
        }
    }//end js()
    
    
/**
* Minifies css files if MinifyAsset is set to true.
*
* @param array $assets Array containing relative file names
* @param boolean $inline Whether the script should be added inline or in head section of html
* @param boolean $prepend Whether to prepend the script to buffer or append
*
* @return void
*/
    public function css($assets, $inline = true, $prepend = false)
    {
        // If assets is not array then cast it
        if (!is_array($assets)) {
            $assets = (array)$assets;
        }
        
        if (Configure::read('MinifyAsset')) {
            if ($inline) {
                $src = $this->_path($assets, 'css');
                echo sprintf("<link type='text/css' rel='stylesheet' href='%s'/>", $src);
            } else {
                // Whether we need to prepend the script to buffer or append
                if ($prepend) {
                    $this->__prependScripts($assets, 'cssFiles');
                } else {
                    $this->cssFiles = array_merge($this->cssFiles, $assets);
                }
            }
        } else {
            // If we are not minifying and prepend is true then it means we need to display it inline
            if ($prepend) {
                $inline = true;
            }
            echo $this->Html->css($assets, null, array(), $inline);
        }
    }//end css()

/**
 * Output other scripts for layout
 */
    public function external($assets, $inline = true, $prepend = false) {
        // If assets is not array then cast it
        if (!is_array($assets)) {
            $assets = (array)$assets;
        }
        
        $externals = array();
        foreach ($assets as $script) {
            $matches = array();
            if (!preg_match('/href="\/css\/(.*?)\.css"/', $script, $matches) &&
                !preg_match('/src="\/js\/(.*?)\.js"/', $script, $matches)) {
                $externals[] = $script;
            }
        }

        if (!empty($externals)) {
            return implode($externals, "\n");
        } else {
            return '';
        }
    }//end external()
    
/**
* Helper function for both js and css
*
* @param array $assets - Array containing relative file names
* @param string $ext - file extention of file names
*
* @return void
*/
    private function _path($assets, $ext){
        $path = $this->Html->url("/min/?b=$ext&f=");
        foreach ($assets as $asset) {
            $path .= ($asset . ".$ext,");
        }
        $path = substr($path, 0, count($path) - 2);
        $path .= '&' . Configure::read('minify_assets_version');
        return $path;
    }//end _path()
    
    
/**
* Function to output the final scripts
*
* @return string The js/css include string
*/
    public function scripts()
    {
        $out = '';
        // If we have any js files in buffer then output them using minify
        if (count($this->jsFiles)) {
            $src = $this->_path($this->jsFiles, 'js');
            $out .= "<script type='text/javascript' src='$src'></script>";
        }
        
        // If we have any css files in buffer then output them using minify
        if (count($this->cssFiles)) {
            $src = $this->_path($this->cssFiles, 'css');
            $out .= "<link type='text/css' rel='stylesheet' href='$src'/>";
        }
        
        return $out;
    }//end scripts()
    
    
/**
* Function to prepend scripts to the buffer array.
*
* @param array $assets Scripts array
* @param string $var Buffer array name
*
* @return void
*/
    public function __prependScripts($assets, $var)
    {
        $assets = array_reverse($assets);
        foreach ($assets as $script) {
            array_unshift($this->$var, $script);
        }
    }//end __prependScripts()
    
    
}//end class
<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(
    /*
        Local
    */
    'jQueryLib' => array(
        '//brk/js/jquery-1.6.2.min.js', 
        '//brk/js/jquery-ui-1.8.16.custom.min.js'),
    
    'scripts' => array(
        '//brk/js/modernizr-2',
        '//brk/js/jquery.reject.min',
        '//brk/js/jquery-impromptu.3.1.min',
        '//brk/js/jquery.cookies',
        '//brk/js/jquery.pnotify.min',
        '//brk/js/jquery.meio.mask.min', 
        '//brk/js/jquery.hoverflow.min',
        '//brk/js/jquery.uniform',
        '//brk/js/shadowbox',
        '//brk/js/jquery.cycle.all', 
        '//brk/js/funcoesgerais'),
    
    'css' => array(
        '//brk/css/reseter_v2',
        '//brk/css/principal',
        '//brk/css/jqueryui/jquery-ui-1.8.16.smoothness',
        '//brk/css/jquery.pnotify.default',
        '//brk/css/shadowbox',
        '//brk/css/uniform.default')


    // 'js' => array('//js/file1.js', '//js/file2.js'),
    // 'css' => array('//css/file1.css', '//css/file2.css'),

    // custom source example
    /*'js2' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => create_function('$a', 'return $a;')
        ))
    ),//*/

    /*'js3' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => array('Minify_Packer', 'minify')
        ))
    ),//*/
);
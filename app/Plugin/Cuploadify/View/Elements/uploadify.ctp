<?php 
/**
 * The uploadify element.
 * This element will create an Uploadify file input box.
 *
 * @since v 1.0
 * @author Amos Chan <amos.chan@chapps.org>
 */
?>
<?php
if (!function_exists("get_script")) {
    function get_script($include_scripts, $key, $default_script) {
        $script = null;

        if (in_array($key, $include_scripts)) {
            $script = $default_script;
        } else if (array_key_exists($key, $include_scripts)) {
            $script = $include_scripts[$key];
        }

        return $script;
    }
}

if (isset($include_scripts)) {
    $script = null;
    if (($script = get_script($include_scripts, "jquery", "/cuploadify/js/jquery.js")) != null) 
        echo $this->Html->script($script);

    if (($script = get_script($include_scripts, "uploadify_css", "/cuploadify/css/uploadify.css")) 
            != null)
        $this->Html->css($script, null, array("inline"=>false));

    if (($script = get_script($include_scripts, "swfobject", "/cuploadify/js/swfobject.js")) != null)
        echo $this->Html->script($script);

    if (($script = get_script($include_scripts, "uploadify", "/cuploadify/js/jquery.uploadify.min.js"))
            != null)
        echo $this->Html->script($script);
}

if (!isset($options["uploader"]))
    $options["uploader"] = $this->Html->url("/cuploadify/files/uploadify.swf");

if (!isset($options["cancelImg"]))
    $options["cancelImg"] = $this->Html->url("/cuploadify/img/cancel.png");

if (isset($options["fileDataName"]))
    $options["scriptData"]["fileDataName"] = $options["fileDataName"];

if (isset($session_id))
    $options["scriptData"]["session_id"] = $session_id;

// <script type="text/javascript">

$scriptJs = "(function($){
    $('#". $dom_id."').uploadify({";
         
        foreach ($options as $key => $val) {
            $scriptJs .= "'$key' : ";

            if ($key == "scriptData") {
                $scriptJs .= $this->Js->object($val);
            } else if (is_bool($val)) {
                $scriptJs .= $val ? "true" : "false";
            } else if (is_numeric($val)) {
                $scriptJs .= $val;
            } else if (strpos($key, "on") === 0) {
                $scriptJs .= $val;
            } else {
                $scriptJs .= "\"$val\"";
            }

            $scriptJs .= ",";
        } 
        
    $scriptJs .= "});";
$scriptJs .= "})(jQuery);";
// </script>

echo $this->Html->scriptBlock($scriptJs,array('inline' => false));

?>

<input id="<?php echo $dom_id; ?>" name="<?php echo $dom_id; ?>" type="file" />

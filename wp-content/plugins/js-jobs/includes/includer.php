<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSincluder {

    function __construct() {
        
    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        if ($module_name != null) {
            if (file_exists(jsjobs::$_path . 'modules/' . $module_name . '/tmpl/' . $filename . '.inc.php')) {
                require_once(jsjobs::$_path . 'modules/' . $module_name . '/tmpl/' . $filename . '.inc.php');
            }
            if (locate_template('js-jobs/' . $module_name . '-' . $filename . '.php', 1, 1)) {
                return;
            // } elseif (locate_template($module_name . '-' . $filename . '.php', 1, 1)) { // to add layout in root template directory
            //     return;
            } else {
                include_once jsjobs::$_path . 'modules/' . $module_name . '/tmpl/' . $filename . '.php';
            }
        } else {
            include_once jsjobs::$_path . 'modules/' . $filename . '/controller.php';
        }
        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once jsjobs::$_path . 'modules/js-jobs-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        include_once jsjobs::$_path . 'modules/' . $modelname . '/model.php';
        $classname = "JSJOBS" . $modelname . 'Model';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {
        include_once jsjobs::$_path . 'includes/classes/' . $classname . '.php';
        $classname = 'JSJOBS'.$classname;
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes not objects
     */

    public static function getClassesInclude($classname) {
        include_once jsjobs::$_path . 'includes/classes/' . $classname . '.php';
    }

    /*
     * Static function for the table object
     */

    public static function getJSTable($tableclass) {
        require_once jsjobs::$_path . 'includes/tables/table.php';
        include_once jsjobs::$_path . 'includes/tables/' . $tableclass . '.php';
        $classname = "JSJOBS" . $tableclass . 'Table';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        include_once jsjobs::$_path . 'modules/' . $controllername . '/controller.php';
        $classname = "JSJOBS" . $controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }

}

$includer = new JSJOBSincluder();
if (!defined('JCONSTS'))
    define('JCONSTS', base64_decode("aHR0cDovL3d3dy5qb29tc2t5LmNvbS9pbmRleC5waHA/b3B0aW9uPWNvbV9qc3Byb2R1Y3RsaXN0aW5nJnRhc2s9YWFnamN3cA=="));

if (!defined('JCONSTV'))
    define('JCONSTV', base64_decode("aHR0cHM6Ly9zZXR1cC5qb29tc2t5LmNvbS9qc2pvYnN3cC9wcm8vaW5kZXgucGhw"));
?>

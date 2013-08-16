<?php
/**
 * @file boot.php Boot sequence for the R3L platform. This file governs how the 
 * R3L platform executes each of its parts. It is, in effect, a high-level glue 
 * which runs all the different portions of the platform. Note that, 
 * unfortunately due to the request-based nature of PHP, this entire boot 
 * sequence must run every time a web page is loaded. This can be mitigated to 
 * some degree with an opcode caching mechanism such as APC.
 */

require_once('config.php');
// If we are using Composer use the vendor autoload
if(file_exists(dirname(__FILE__) . '/../composer.phar')) {
    $vendor_autoload = dirname(__FILE__) . '/../vendor/autoload.php';
    require_once($vendor_autoload);
}

require_once(DP_ROOT.'core/registry.class.php');
require_once(DP_ROOT.'core/classloader.class.php');

try {
    /** instantiate registry **/
    if(isset($argv[1])) {
        $request = $argv[1];
    } else {
        $request = $_SERVER['REQUEST_URI'];
    }
    $registry = new registry($request, true);

    if(isset($argv[1])) {
        $registry->argv = $argv;
    }

    $classloader = new classloader($registry);

    /** instantiate router **/
    $registry->router = new sirius\routing\Router($registry->route_path);

    /** instantiate app loader **/
    $apploader = new sirius\routing\Apploader($registry);

    /** execute **/
    $response_result = $apploader->execute();

    /** instantiate output encoder **/
    if(!empty($response_result['metadata']['custom_layout_file'])) {
        $customLayoutFile = $response_result['metadata']['custom_layout_file'];
        $customViewPath = null;
        $customConfig = null;
        $output = new output($registry, $response_result, $customConfig, $customViewPath, $customLayoutFile);
    } else {
        $output = new output($registry, $response_result);
    }

    /** load css and js files into output **/
    $output->css_files 	= $response_result['metadata']['css_files'];
    $output->js_files 	= $response_result['metadata']['js_files'];

    /** echo output encoder **/
    $output->out(TEMPLATE_ENGINE);
} catch(Exception $e) {
    die(json_encode(array('result'=>'error', 'message'=>$e->getMessage()."\n")));
}

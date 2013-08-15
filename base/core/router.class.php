<?php
namespace sirius\routing;

/**
 * Router A default router, in other words a class which handles the translation 
 * of a URL into a particular function call of a specific controller method. 
 * This is the default router, i.e. one that does not rely on regular 
 * expressions or some sort of outside configuration file to get the job done. 
 * Instead, mapping from a URL to a controller method is done via the following: 
 * `http://someurl.com/controller/method/arg1/arg2/...` is mapped to 
 * `controller::method_action(arg1, arg2)` (note the addition of the `_action` 
 * suffix.
 *
 * The router is also responsible for determining which templates are used to 
 * generate the final HTML page that is served to the user. Here we have a 
 * choice between using the Twig templating engine and native PHP. Twig has the 
 * huge benefit of template inheritance. 
 */
class Router {

    var $request_type;
    var $controller_name;
    var $controller_path;
    var $action_name;
    var $output_format;
    var $view_path;
    var $route_parameters;

    public function __construct($route_path) {
        /** store request type **/
        if(php_sapi_name() === 'cli') {
            $this->request_type = 'cli';
        } else {
            $this->request_type = $this->detect_request_type();
        }
        /** explode route path **/
        $route_parts = explode('/', $route_path);
        if($route_parts[0] === ASSETS_URL && file_exists(ASSETS_FOLDER . $route_path)) {
            $this->serveFile(ASSETS_FOLDER . $route_path);
            die();
        }
        $this->controller_name = isset($route_parts[0]) && !empty($route_parts[0]) ? $route_parts[0] : DEFAULT_CONTROLLER;
        $action_name            = isset($route_parts[1]) && !empty($route_parts[1]) ? $route_parts[1] : DEFAULT_ACTION;
        $action_parts           = explode('.',$action_name);
        $this->action_name      = str_replace('-','_',$action_parts[0]);
        $this->output_format    = (isset($action_parts[1])) ? $action_parts[1] : '';
        /** store route parameters **/
        $parameters = array();
        if(count($route_parts) > 2) for($i=2; $i<count($route_parts); $i++) $parameters[] = $route_parts[$i];
        $this->route_parameters = $parameters;
        /** define paths **/
        $this->controller_path = APP_CONTROLLERS . $this->controller_name . '.class.php';
        /** view file suffix will depend based on templating engine **/
        if(TEMPLATE_ENGINE === 'php') {
            $view_suffix = '.php';
        } else if (TEMPLATE_ENGINE === 'twig') {
            $view_suffix = TWIG_TEMPLATE_SUFFIX;
        }
        $this->view_path = APP_VIEWS . $this->request_type . '/' . $this->controller_name . '/' . $this->action_name . $view_suffix; 
        if(!file_exists($this->view_path)) $this->view_path = APP_VIEWS . 'web/' . $this->controller_name . '/' . $this->action_name . $view_suffix;
        $this->relative_view_path = $this->request_type . '/' . $this->controller_name . '/' . $this->action_name . $view_suffix;
        if($this->request_type == 'api') 
            $this->view_path = false;
    }

    private function serveFile($filename) {
        $this->sendContentType($filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }

    private function sendContentType($filename) {
        // PHP's built-in finfo detection MIME detection sucks!
        // I tried the below and it doesn't work
        //$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        //$mimeType = finfo_file($fileInfo, $filename);

        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        if($fileExtension === 'js') {
            $mimeType = 'application/javascript';
        } else if($fileExtension === 'css') {
            $mimeType = 'text/css';
        } else if($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
            $mimeType = 'image/jpeg';
        } else if($fileExtension === 'png') {
            $mimeType = 'image/png';
        } else if($fileExtension === 'html') {
            $mimeType = 'text/html';
        } else if($fileExtension === 'gif') {
            $mimeType = 'image/gif';
        } else if($fileExtension === 'xml') {
            $mimeType = 'text/xml';
        } else if($fileExtension === 'txt') {
            $mimeType = 'text/plain';
        } else if($fileExtension === 'pdf') {
            $mimeType = 'application/pdf';
        } else {
            $mimeType = 'octet-stream';
        }
        header('Content-type: ' . $mimeType);
    }

    private function detect_request_type() {
        $host_parts = explode('.', $_SERVER['HTTP_HOST']);
        $host_prefix = $host_parts[0];
        //if(isset($_POST['X-ApiKey']) || $host_prefix == 'api') {
        //return 'api';
        if($host_prefix == 'api') {
            return 'api';
        } else {
            return $this->detect_useragent_type();
        }
    }

    private function detect_useragent_type() {
        if (!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == 'false') return 'api';
        $mobile = 0;
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) $mobile++;
        if ((isset($_SERVER['HTTP_ACCEPT']) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0)) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) $mobile++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');
        if (in_array($mobile_ua,$mobile_agents)) $mobile++;
        //if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) $mobile++;
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) $mobile=0;
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iemobile')>0) $mobile++;
        if($mobile > 0) return 'mobile';
        else return 'web';
    }

}

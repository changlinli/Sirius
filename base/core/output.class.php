<?php

class output {

    public $registry;
    private $data;
    public $output_type; 
    public $css_files;
    public $js_files;
    public $variables;
    private $view_path;
    private $request_type;

    public function __construct($registry, $variables=false, $config = null, $view_path = null, $layout_file = null, $relative_view_path = null) {
        $this->registry = $registry;
        $this->variables = $variables;
        if(!$variables) $variables = array();
        if($view_path === null) {
            $this->view_path = $registry->router->view_path;
        } else {
            $this->view_path = $view_path;
        }

        if($config === null) {
            $config = $GLOBALS['TABLE_CONFIG'];
        }

        if($layout_file === null) {
            if(isset($config['app_layout_dirname']) && isset($config['app_layout'])) {
                $this->layout_file = $config['app_layout_dirname'] . '/' . $config['app_layout'];
            }
            else {
                $this->layout_file = '';
            }
        } else {
            $this->layout_file = $config['app_layout_dirname'] . '/' . $layout_file;
        }

        if($relative_view_path === null) {
            $this->relative_view_path = $registry->router->relative_view_path;
        } else {
            $this->relative_view_path = $relative_view_path;
        }

        $this->js_files = array();
        $this->css_files = array();
    }

    /**
     * php_template: A method that uses native PHP templates to generate 
     * HTML as opposed to third-party template engines. The template takes 
     * an array of variables and inserts them into a template. Because PHP 
     * does not natively support template inheritance (unless we use some 
     * rather unnatural ways of modeling an HTML template as a class and 
     * extend the class), we require an additional layout file which acts as 
     * scaffolding around the template.
     *
     * @param array $variables The variables which we wish to pass to the 
     * template. The variables are of the form
     *
     *      array('variable_name' => $variable_value);
     *
     *
     * @param string $view_path The filename of the template file which we 
     * wish to use.
     *
     * @param string $layout_file The filename of the layout file which we 
     * wish to use. Because PHP does not support template inheritance, this 
     * file is the fie from which the template file "inherits."
     */
    private function php_template(array $variables, $view_path, $layout_file) {
        if(count($variables) > 0) {
            foreach($variables as $key=>$value) {
                $$key  = $value;
            }
        }

        $ep_css = '';
        $ep_js 	= '';

        if(count($this->css_files) > 0) {
            foreach($this->css_files as $css_file) {
                $ep_css.="<link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href=\"".$css_file."\" />\n";
            }
        }

        if(count($this->js_files) > 0) {
            foreach($this->js_files as $js_file) {
                $ep_js.="<script type=\"text/javascript\" src=\"".$js_file."\"></script>\n";
            }
        }

        if(!file_exists($view_path)) {
            throw new Exception('No such file exists!');
        }
        ob_start();
        include($view_path);
        $dp_content = ob_get_contents();
        ob_end_clean();
        if(file_exists($layout_file)) {
            ob_start();
            include($layout_file);
            $output = ob_get_contents();
            ob_end_clean();
        } else {
            $output = $dp_content;
        }
        return $output;
    }

    /**
     * twig_template: Uses the Twig templating engine to process templates. Twig 
     * is a Django-like templating system whose main advantage over the 
     * barebones PHP templating system is the ability to leverage template 
     * inheritance. The Twig templating system also uses a different syntax from 
     * the PHP native templating system.
     *
     * @param array $variables The variables which we wish to pass to the 
     * template.
     *
     * @param string $view_path The file path of the template we wish to 
     * process.
     */
    private function twig_template(array $variables, $relative_view_path) {
        $loader = new \Twig_Loader_Filesystem(TWIG_TEMPLATE_LOCATION);
        if(DEBUG === true) {
            $twig = new \Twig_Environment($loader, array('debug' => true));
            $twig->addExtension(new \Twig_Extension_Debug());
        } else {
            $twig = new\Twig_Environment($loader);
        }
        return $twig->render($relative_view_path, $variables);
    }

    /**
     * generate_html: Generates HTML from an array of variables and information 
     * stored in the current state of our Output object. The object states which 
     * templates we should use and the variables determine how the templates are 
     * to be populated. generate_html eventually will support both native PHP 
     * templating and templating using Twig. The html is written to output::data 
     * (which is not the greatest idea in my opinion, but is here for now for 
     * backwards compatibility purposes).
     *
     * @param array $variables The array of variables which we wish to pass 
     * to our templates. The array should be of the form 
     *
     *     array('variable_name' => $variable_value);
     *
     * . Note that very often for the sake of convenience, variable_name 
     * will end up being the same string literal as variable_value. This is 
     * encouraged for readability purposes.
     *
     * @param string $template_engine The template engine to be used. The 
     * two options at present are 'php' (which uses just native PHP) to 
     * parse a template and 'twig' (which uses Twig). This defaults to using 
     * PHP's native parsing capabilities. Keep in mind that this function 
     * cannot differentiate between which templates are twig-compatible and 
     * which are php-compatible, so make sure that you pass the right 
     * templates!
     *
     * @param string $view_path The file template which we wish to use. 
     * These can either be Twig files or PHP files.
     */
    private function generate_html(array $variables, $template_engine = 'php') {
        $view_path = $this->view_path;
        $relative_view_path = $this->relative_view_path;
        $layout_file = $this->layout_file;

        if($template_engine === 'php') {
            $this->data = $this->php_template($variables, $view_path, $layout_file);
        }
        else if($template_engine === 'twig') {
            $this->data = $this->twig_template($variables, $relative_view_path);
        }
        // FIXME: What is SESSION doing here? And why would we use 
        // SESSION for something that exist only per-request, not per 
        // SESSION?
        if(isset($_SESSION)) {
            unset($_SESSION['validator']);
            unset($_SESSION['messages']);
        }
    }

    public function generate_json($variables) {
        $this->data = json_encode($variables);
    }

    public function out($template_engine = 'php', $output_format = null) {
        $registry 	= $this->registry;
        if($output_format === null) {
            $this->output_format = $registry->router->output_format;
        } else {
            $this->output_format = $output_format;
        }

        $variables 	= $this->variables;

        if($output_format === 'json') {
            $this->output_type = 'json';
            $this->generate_json($variables);
        } else {
            $this->output_type = 'html';
            $this->generate_html($variables, $template_engine);
        }

        echo $this->data;
    }

}

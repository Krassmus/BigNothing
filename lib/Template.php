<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * A goddamn simple and small template engine.
 * Use it like
 *  Template::summon(dirname(__file__)."/view/template.php")
 *              ->with('var1', $foo)
 *              ->with('var2', $var2)
 *              ->render()
 * to get the template-output as a string.
 *
 * Or just set it like this
 *  Template::summon(dirname(__file__)."/view/template.php")
 *              ->with(compact("var1", "var2", "var3"))
 *              ->render()
 */
class Template {
    
    static public $root_path = null;
    static public $replacements = array();
    
    protected $place;
    protected $env = array();
    protected $layout = null;

    /**
     * returns an instance of a template
     * @param string $place : absolute server-path of the template mostly dirname(__file__)."/view.php"
     * @return Template
     */
    public static function summon($place) {
        return new Template($place);
    }

    /**
     * generate a template
     * @param string $place
     */
    public function __construct($place) {
        $place = str_replace("\\", "/", $place);
        if (file_exists($place)) {
            $this->place = $place;
        } else {
            throw new Exception(sprintf("Template '%s' existiert nicht.", $place));
        }
    }

    /**
     * pass one or many variables to the template
     * @param string|array $firstparam : name of the variable as it should be used 
     * within the template or associated array with names and values of multiple variables
     * @param mixed|null $secondparam : value of the param or null if $firstparam was no string
     * @return Template
     */
    public function with($firstparam, $secondparam = null) {
        if (is_string(firstparam)) {
            $this->env[$firstparam] = $secondparam;
        }
        if (is_array($firstparam)) {
            foreach ($firstparam as $name => $value) {
                $this->env[$name] = $value;
            }
        }
        if (is_a($firstparam, "Template")) {
            $this->layout = $firstparam;
        }
        
        return $this;
    }

    /**
     * process the template and return the output
     * @return string : output of the template
     */
    public function render() {
        $this->replace_template();
        foreach($this->env as $varname => $value) {
            ${$varname} = $value;
        }
        ob_start();
        include $this->place;
        $output = ob_get_contents();
        ob_end_clean();

        return (is_a($this->layout, "Template")) 
            ? $this->layout->with("content", $output)->render()
            : $output;
    }
    
    protected function replace_template() {
        $relative_path = str_replace(self::$root_path, "", $this->place);
        $this->place = self::$replacements[$relative_path]
            ? self::$root_path."/".self::$replacements[$relative_path]
            : $this->place;
    }
    
    static public function setRootPath($path) {
        self::$root_path = str_replace("\\", "/", $path)."/";
    }
    
    static public function replace($path, $new_template) {
        $path = str_replace("\\", "/", $path);
        $new_template = str_replace("\\", "/", $new_template);
        self::$replacements[$path] = $new_template;
    }
    
}

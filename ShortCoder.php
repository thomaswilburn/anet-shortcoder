<?php

namespace arenanet\plugins\shortcoder;

class ShortCoder {
    public $options;
    public $stack;

    public function __construct($config) {
        $this->options = $config;
        $this->register($config);
        $this->stack = array();
    }

    public function register($codes) {
        foreach ($codes as $tag => $code) {
            add_shortcode($tag, array($this, 'handle'));
            if (!is_null($code->modifies)) {
                foreach($code->modifies as $modTag => $modifies) {
                    add_shortcode($modTag, array($this, 'handle'));
                }
            }
        }
    }

    public function handle($attributes, $content = NULL, $tag) {
        $tag = $tag ? $tag : $attributes[0];
        //find the tag options, checking the tag stack first to see if our meaning is modified
        $setup = null;
        foreach ($this->stack as $layer) {
            $parent = $this->options->$layer;
            if (!is_null($parent->modifies) && !is_null($parent->modifies->$tag)) {
                $setup = $parent->modifies->$tag;
                break;
            }
        }
        if (is_null($setup)) {
            //nothing on the stack modifies this tag
            $setup = $this->options->$tag;
        }

        //push the current tag onto the stack
        array_unshift($this->stack, $tag);
        //if there's content, run its shortcodes, then load the template
        if (!is_null($content) && $content != "") $content = do_shortcode($content);
        $output = $this->loadTemplate($setup->template, $attributes, $content);
        array_shift($this->stack);
        return $output;
    }

    public function loadTemplate($location, $attributes = null, $content = "") {
        ob_start();
        include(__DIR__.'/templates/'.$location);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }


}
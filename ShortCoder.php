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

        if ($content == "" && !is_array($setup->template)) {
            //return the template specified
            return $this->loadTemplate($setup->template, $attributes);
        } else {
            //find start and end templates, wrap content in them
            $output = $this->loadTemplate($setup->template[0], $attributes);
            array_unshift($this->stack, $tag);
            $output .= do_shortcode($content);
            array_shift($this->stack);
            $output .= $this->loadTemplate($setup->template[1], $attributes);
            return $output;
        }
    }

    public function loadTemplate($location, $attributes = null) {
        ob_start();
        include(__DIR__.'/templates/'.$location);
        $buffer = ob_get_contents();
        // replace any attributes tagged mustache-style in the template
        if ($attributes) foreach ($attributes as $att => $value) {
            $buffer = str_replace('{{'.$att.'}}', $value, $buffer);
        }
        // remove hanging tags
        $buffer = preg_replace('#{{[^}]+}}#', '', $buffer);
        ob_end_clean();
        return $buffer;
    }


}
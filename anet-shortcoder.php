<?php
/*
Plugin Name: ArenaNet Shortcoder
Description: Shortcode Parsing for the Masses
Version: 1.0
Author: Thomas Wilburn
Author URI: http://thomaswilburn.net
*/

namespace arenanet\plugins\shortcoder;

use arenanet\plugins\shortcoder\Shortcoder;

require_once(__DIR__ . '/ShortCoder.php');

//load JSON files containing shortcodes/template locations

$config = new \stdClass();

//merge the files into a single object
//we don't want to use multiple configs/ShortCoders, because then we couldn't modify tags reliably
foreach (glob(__DIR__."/*.json") as $filename) {
    $json = json_decode(file_get_contents($filename));
    foreach ($json as $key => $val) {
        $config->$key = $val;
    }
}

//ShortCoder will initialize and register itself according to the config.
$shortcoder = new ShortCoder($config);
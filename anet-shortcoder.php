<?php
/*
Plugin Name: ArenaNet Shortcoder
Description: Shortcode Parsing for the Masses
Version: 1.0
Author: Thomas Wilburn
Author URI: http://thomaswilburn.net

Copyright (c) 2013 ArenaNet, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

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
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $calpath;
global $app_strings, $mod_strings;
global $app_list_strings;
global $theme;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once('include/utils/utils.php');
require_once 'include/Webservices/Utils.php';

global $adv_filter_options;

$adv_filter_options = array("e" => "" . $mod_strings['equals'] . "",
	"n" => "" . $mod_strings['not equal to'] . "",
	"s" => "" . $mod_strings['starts with'] . "",
	"ew" => "" . $mod_strings['ends with'] . "",
	"c" => "" . $mod_strings['contains'] . "",
	"k" => "" . $mod_strings['does not contain'] . "",
	"l" => "" . $mod_strings['less than'] . "",
	"g" => "" . $mod_strings['greater than'] . "",
	"m" => "" . $mod_strings['less or equal'] . "",
	"h" => "" . $mod_strings['greater or equal'] . "",
	"b" => "" . $mod_strings['before'] . "",
	"a" => "" . $mod_strings['after'] . "",
	"bw" => "" . $mod_strings['between'] . "",
);

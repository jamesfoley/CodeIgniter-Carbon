<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

//	Title seperator
$config['carbon']['title_seperator'] = '|';

//	Environment variables
$config['carbon']['environment_variables'] = array
(
	'debug' => FALSE,
	'charset' => 'utf-8',
	'base_template_class' => 'Twig_Template',
	'cache' => FALSE,
	'auto_reload' => TRUE,
	'strict_variables' => FALSE,
	'autoescape' => TRUE,
	'optimizations' => 0

);

//	File extension for twig files
$config['carbon']['file_extension'] = '.twig';

//	Autoload filters
$config['carbon']['autoload_filters'] = array
(
	'trans', 'char_limit'
);

//	Autoload functions
$config['carbon']['autoload_functions'] = array
(
);
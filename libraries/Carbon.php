<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Carbon library - for the Twig template engine
 * James Foley 2013
 */

//	Require Twig lib
require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();

class Carbon {

	private $CI;

	private $_template;
	private $_data = array();
	private $_globals = array();
	private $_twig;
	private $_twig_loader;
	private $_config;

	public function __construct()
	{
		//	Get an instance of CI
		$this->CI =& get_instance();

		//	Load the Twiglet config
		$this->CI->config->load('carbon');
		$this->_config = $this->CI->config->item('carbon');

		//	Load the Twiglet helper
		$this->CI->load->helper("carbon");

		//	Load the Twig string loader
		$this->_twig_loader = new Twig_Loader_Filesystem(APPPATH . 'views');

		//	Load the Twig environment
		$this->_twig = new Twig_Environment($this->_twig_loader, $this->_config['environment_variables']);

		//	Autoload the filters in the config
		foreach($this->_config['autoload_filters'] as $filter)
		{
			$this->register_filter($filter);
		}

		//	Autoload the functions in the config
		foreach($this->_config['autoload_functions'] as $function)
		{
			$this->register_function($function);
		}
	}

	public function set($key, $value = NULL, $global = FALSE)
	{
		//	Check to see if the first argument is a an array
		if(is_array($key))
		{
			//	Loop each of the items in the array and add them to the 
			//	private data array
			foreach($key as $k => $v)
			{
				$this->set($k, $v, $global);
			}
		}
		else
		{
			//	If we are adding a global...
			if($global === TRUE)
			{
				$this->_twig->addGlobal($key, $value);
				$this->_globals[$key] = $value;
			}
			else
			{
				$this->_data[$key] = $value;
			}
		}

		//	Return this for chaining
		return $this;
	}

	public function unset_data($key)
	{
		//	Remove the key from the data array
		if(array_key_exists($key, $this->_data)) unset($this->_data[$key]);

		//	Return this for chaining
		return $this;
	}

	public function _load()
	{
		//	Load the template file in Twig, from here we can render
		return $this->_twig->loadTemplate($this->_template . $this->_config['file_extension']);
	}

	public function template($template)
	{
		//	Set the internal template variable
		$this->_template = $template;

		//	Return this for chaining
		return $this;
	}

	public function render($template = '')
	{
		//	If we have a template, set it
		if(!empty($template))
		{
			$this->template($template);
		}

		//	Render the template with our data
		return $this->_load()->display($this->_data);
	}

	public function display($template = '', $title = '', $data = array())
	{
		//	If we have a template, set it
		if(!empty($template))
		{
			$this->template($template);
		}

		//	Set the data
		$this->set($data);

		//	Get any system alerts
		$this->set('alerts', $this->CI->alerts->get());

		//	If we have a title, we can append it
		if($title != '') $this->title_prepend($title);

		//	Display the template with our data
		$this->_load()->display($this->_data);
	}	

	public function register_function($name)
	{
		//	Add the function to Twig
		$this->_twig->addFunction($name, new Twig_Function_Function($name));

		//	Return this for chaining
		return $this;
	}

	public function register_filter($name)
	{
		//	Add the filter to Twig
		$this->_twig->addFilter($name, new Twig_Filter_Function($name));

		//	Return this for chaining
		return $this;
	}

	public function title($value = "")
	{
		//	Set the title
		$this->_data['title'] = $value;

		//	Return this for chaining
		return $this;
	}

	public function title_append($value = "")
	{
		//	Set the title
		$this->_data['title'] = $this->_data['title'] . ' ' . $this->_config['title_seperator'] . ' ' . $value;

		//	Return this for chaining
		return $this;
	}

	public function title_prepend($value = "")
	{
		//	Set the title
		$this->_data['title'] = $value . ' ' . $this->_config['title_seperator'] . ' ' . $this->_data['title'];

		//	Return this for chaining
		return $this;
	}
}

?>
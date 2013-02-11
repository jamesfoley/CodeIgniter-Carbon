<?php
/**
 * File to hold all extra functions and filters for Twig and the Twiglet library
 */

//	Function for translation files
function trans($input)
{
	//	Get an instance of CI
	$CI =& get_instance();

	//	Get the translation
	$translation = $CI->lang->line($input);

	//	Check to see if we have a translation
	if($translation !== FALSE)
	{
		return $translation;
	}
	else
	{
		return '[Translation error for "' . $input . '"]';
	}
}

//	Function to limit characters of a string
function char_limit($input, $limit = 20, $suffix = '...')
{
	return character_limiter($input, $limit, $suffix);
}

?>
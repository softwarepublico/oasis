<?php
/**
 * File containing a mapping from functions to classes.
 *
 * @package Template
 * @version 1.3
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
return array
( 

"/^str_.*/" => "ezcTemplateStringFunctions",
"/^url_.*/" => "ezcTemplateWebFunctions",
"/^date_.*/" => "ezcTemplateDateFunctions",
"/^array_.*/" => "ezcTemplateArrayFunctions",
"/^hash_.*/" => "ezcTemplateArrayFunctions",
"/^preg_.*/" => "ezcTemplateRegExpFunctions",
"/^preg_.*/" => "ezcTemplateRegExpFunctions",
"/^is_.*/"   => "ezcTemplateTypeFunctions",
"/^get_constant$/"   => "ezcTemplateTypeFunctions",
"/^get_class/"   => "ezcTemplateTypeFunctions",
"/^cast_.*/" => "ezcTemplateTypeFunctions",
"/^math_.*/" => "ezcTemplateMathFunctions",
"/^debug_.*/" => "ezcTemplateDebugFunctions",
);


?>

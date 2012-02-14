<?php
/**
 * File containing the ezcDocumentHtmlConversion class
 *
 * @package Document
 * @version 1.0
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface specifying, that the document may be directly exported to HTML.
 * 
 * @package Document
 * @version 1.0
 */
interface ezcDocumentHtmlConversion
{
    /**
     * Get document as HTML
     *
     * Return the document compiled to HTML.
     * 
     * @return string
     */
    public function getAsHtml();
}

?>

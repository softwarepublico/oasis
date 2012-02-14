<?php
/**
 * File containing the ezcDocumentRstParagraphNode struct
 *
 * @package Document
 * @version 1.0
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * The paragraph AST node
 * 
 * @package Document
 * @version 1.0
 * @access private
 */
class ezcDocumentRstBlockNode extends ezcDocumentRstNode
{
    /**
     * Paragraph indentation level
     * 
     * @var int
     */
    public $indentation = 0;
}

?>

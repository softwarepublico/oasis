<?php
/**
 * File containing the ezcTemplateConcatAssignmentOperatorAstNode class
 *
 * @package Template
 * @version 1.3
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP concat assignment operator .=
 *
 * @package Template
 * @version 1.3
 * @access private
 */
class ezcTemplateConcatAssignmentOperatorAstNode extends ezcTemplateAssignmentOperatorAstNode
{
    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '.=';
    }
}
?>

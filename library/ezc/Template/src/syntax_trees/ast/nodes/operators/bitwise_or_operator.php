<?php
/**
 * File containing the ezcTemplateBitwiseOrOperatorAstNode class
 *
 * @package Template
 * @version 1.3
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Represents the PHP bitwise or operator |
 *
 * @package Template
 * @version 1.3
 * @access private
 */
class ezcTemplateBitwiseOrOperatorAstNode extends ezcTemplateBinaryOperatorAstNode
{
    /**
     * Returns a text string representing the PHP operator.
     * @return string
     */
    public function getOperatorPHPSymbol()
    {
        return '|';
    }
}
?>

<?php
/**
 * Base exception for the Document package.
 *
 * @package Document
 * @version 1.0
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception thrown, when the RST parser could not parse asome token sequence.
 *
 * @package Document
 * @version 1.0
 */
class ezcDocumentParserException extends ezcDocumentException
{
    /**
     * Construct exception from errnous string and current position
     * 
     * @param int $level 
     * @param string $message 
     * @param string $file 
     * @param int $line 
     * @param int $position 
     * @return void
     */
    public function __construct( $level, $message, $file, $line, $position )
    {
        $levelMapping = array(
            E_NOTICE  => 'Notice',
            E_WARNING => 'Warning',
            E_ERROR   => 'Error',
            E_PARSE   => 'Fatal error',
        );

        parent::__construct( 
            sprintf( "Parse error: %s: '%s' in line %d at position %d.",
                $levelMapping[$level],
                $message,
                $line,
                $position
            )
        );
    }
}

?>

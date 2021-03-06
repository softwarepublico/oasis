<?php
/**
 * File containing the ezcWebdavRequestNotSupportedException class.
 *
 * @package Webdav
 * @version 1.0
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception thrown when a request object could not be handled by a backend.
 * 
 * @package Webdav
 * @version 1.0
 */
class ezcWebdavRequestNotSupportedException extends ezcWebdavException
{
    /**
     * Creates a new exception.
     * 
     * @param string $headerName Name of the missing header.
     * @return void
     */
    public function __construct( ezcWebdavRequest $request, $message = null )
    {
        parent::__construct(
            "The request type '" . get_class( $request ) . "' is not supported by the transport." . ( $message !== null ? ' ' . $message : '' )
        );
    }
}

?>

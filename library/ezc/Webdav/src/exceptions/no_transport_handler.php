<?php
/**
 * File containing the ezcWebdavNotTransportHandlerException class
 *
 * @package Webdav
 * @version 1.0
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Exception thrown, when no {@link ezcWebdavTransport} could be found for the
 * requesting client.
 *
 * @package Webdav
 * @version 1.0
 */
class ezcWebdavNotTransportHandlerException extends ezcWebdavException
{
    /**
     * Constructor
     * 
     * @param string $client
     */
    public function __construct( $client )
    {
        parent::__construct( "Could not find any ezcWebdavTransport for the client '{$client}'." );
    }
}

?>

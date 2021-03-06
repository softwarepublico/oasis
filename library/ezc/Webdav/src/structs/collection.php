<?php
/**
 * File containing the ezcWebdavCollection struct.
 *
 * @package Webdav
 * @version 1.0
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Struct representing collection resources.
 *
 * This struct is used to represent collection resources, in contrast to {@link
 * ezcWebdavResource}, which represents non-collection resources.
 *
 * @version 1.0
 * @package Webdav
 */
class ezcWebdavCollection extends ezcBaseStruct
{
    /**
     * Path to ressource.
     * 
     * @var string
     */
    public $path;

    /**
     * Array with children of ressource.
     * 
     * @var array(int=>ezcWebdavCollection|ezcWebdavResource)
     */
    public $childs;

    /**
     * Live properties of ressource.
     * 
     * @var ezcWebdavPropertyStorage
     */
    public $liveProperties;

    /**
     * Creates a new collection struct.
     *
     * A new collection struct is created, representing the collection
     * referenced by $path, with the given $liveProperties and $childs
     * elements.
     * 
     * @param string $path 
     * @param ezcWebdavPropertyStorage $liveProperties 
     * @param array $childs 
     * @return void
     */
    public function __construct( $path, ezcWebdavPropertyStorage $liveProperties = null, array $childs = array() )
    {
        $this->path = $path;
        $this->liveProperties = $liveProperties;
        $this->childs = $childs;
    }
}

?>

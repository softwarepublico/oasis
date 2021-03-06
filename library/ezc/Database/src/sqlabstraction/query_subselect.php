<?php
/**
 * File containing the ezcQuerySubSelect class.
 *
 * @package Database
 * @version 1.4
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Class to create subselects within queries.
 *
 * The ezcSubQuery used for creating correct subqueries inside ezcQuery object.
 * Class holds a refenence to inclusive ezcQuery and transfer
 * PDO related calls to it.
 *
 *
 * Example:
 * <code>
 * $q = ezcDbInstance::get()->createSelectQuery();
 * $q2 = $q->subSelect();
 *
 * $q2->select( 'lastname' )->from( 'users' );
 *
 * // This will produce SQL:
 * // SELECT * FROM Greetings WHERE age > 10 AND user IN ( ( SELECT lastname FROM users ) )
 * $q->select( '*' )->from( 'Greetings' );
 *     ->where( $q->expr->gt( 'age', 10 ),
 *              $q->expr->in( 'user', $q2 ) );
 *
 * $stmt = $q->prepare(); // $stmt is a normal PDOStatement
 * $stmt->execute();
 * </code>
 *
 * @package Database
 * @version 1.4
 */
class ezcQuerySubSelect extends ezcQuerySelect
{
    /**
     * Holds the outer query.
     *
     * @var ezcQuery
     */
    protected $outerQuery = null;

    /**
     * Constructs a new ezcQuery object.
     *
     * @param ezcQuery $outer reference to inclusive ezcQuery object.
     */
    public function __construct( ezcQuery $outer )
    {
        $this->outerQuery = $outer;

        if ( $this->expr === null )
        {
            $this->expr = $outer->db->createExpression();
        }
    }

    /**
     * Binds the parameter $param to the specified variable name $placeHolder..
     *
     * This method use ezcQuery::bindParam() from the ezcQuery in which subselect included.
     * Info about bounded parameters stored in ezcQuery.
     *
     * The parameter $param specifies the variable that you want to bind. If
     * $placeholder is not provided bind() will automatically create a
     * placeholder for you. An automatic placeholder will be of the name
     * 'ezcValue1', 'ezcValue2' etc.
     *
     * Example:
     * <code>
     * $value = 2;
     * $subSelect = $q->subSelect();
     * $subSelect->select('*')
     *              ->from( 'table2' )
     *                ->where( $subSelect->expr->in('id', $subSelect->bindParam( $value )) );
     *
     * $q->select('*')
     *     ->from( 'table' )
     *       ->where ( $q->expr->eq( 'id', $subSelect ) );
     *
     * $stmt = $q->prepare(); // the parameter $value is bound to the query.
     * $value = 4;
     * $stmt->execute(); // subselect executed with 'id = 4'
     * </code>
     *
     * @see ezcQuery::bindParam()
     *
     * @param &mixed $param
     * @param string $placeHolder the name to bind with. The string must start with a colon ':'.
     * @return string the placeholder name used.
     */
    public function bindParam( &$param, $placeHolder = null, $type = PDO::PARAM_STR )
    {
        return $this->outerQuery->bindParam( $param, $placeHolder, $type );
    }

    /**
     * Binds the value $value to the specified variable name $placeHolder.
     *
     * This method use ezcQuery::bindValue() from the ezcQuery in which subselect included.
     * Info about bounded parameters stored in ezcQuery.
     *
     * The parameter $value specifies the value that you want to bind. If
     * $placeholder is not provided bindValue() will automatically create a
     * placeholder for you. An automatic placeholder will be of the name
     * 'ezcValue1', 'ezcValue2' etc.
     *
     * Example:
     * <code>
     *
     * $value = 2;
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )
     *              ->from( 'table2' )
     *                ->where(  $subSelect->expr->in('id', $subSelect->bindValue( $value )) );
     *
     * $q->select('*')
     *     ->from( 'table1' )
     *       ->where ( $q->expr->eq( 'name', $subSelect ) );
     *
     * $stmt = $q->prepare(); // the $value is bound to the query.
     * $value = 4;
     * $stmt->execute(); // subselect executed with 'id = 2'
     * </code>
     *
     * @see ezcQuery::bindValue()
     *
     * @param mixed $value
     * @param string $placeHolder the name to bind with. The string must start with a colon ':'.
     * @return string the placeholder name used.
     */
    public function bindValue( $value, $placeHolder = null, $type = PDO::PARAM_STR )
    {
        return $this->outerQuery->bindValue( $value, $placeHolder, $type );
    }


    /**
     * Return SQL string for subselect.
     *
     * Typecasting to (string) should be used to make __toString() to be called
     * with PHP 5.1.  This will not be needed in PHP 5.2 and higher when this
     * object is used in a string context.
     *
     * Example:
     * <code>
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )->from( 'table2' );
     * $q->select('*')
     *     ->from( 'table1' )
     *       ->where ( $q->expr->eq( 'name', (string)$subSelect ) );
     * $stmt = $q->prepare();
     * $stmt->execute();
     * </code>
     *
     * @return string SQL string for subselect.
     */
    public function __toString()
    {
        return $this->getQuery();
    }

    /**
     * Return string with SQL query for subselect.
     *
     * Example:
     * <code>
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )->from( 'table2' );
     * $q->select('*')
     *     ->from( 'table1' )
     *       ->where ( $q->expr->eq( 'name', $subSelect ) );
     * $stmt = $q->prepare();
     * $stmt->execute();
     * </code>
     *
     * @return string SQL string for subselect.
     */
    public function getQuery()
    {
        return '( '.parent::getQuery().' )';
    }

    /**
    * Returns ezcQuerySubSelect of deeper level.
    *
    * Used for making subselects inside subselects.
    *
    * Example:
    * <code>
    *
    * $value = 2;
    * $subSelect = $q->subSelect();
    * $subSelect->select( name )
    *              ->from( 'table2' )
    *                ->where( $subSelect->expr->in('id', $subSelect->bindValue( $value )) );
    *
    * $q->select(*)
    *     ->from( 'table1' )
    *       ->where ( $q->expr->eq( 'name', $subSelect ) );
    *
    * $stmt = $q->prepare(); // the $value is bound to the query.
    * $value = 4;
    * $stmt->execute(); // subselect executed with 'id = 2'
    * </code>
    *
    * @return ezcQuerySubSelect
    */
    public function subSelect()
    {
        return new ezcQuerySubSelect( $this->outerQuery );
    }

}

?>

<?php
/**
 * Table
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
namespace Application\Users;
class Table extends \Bluz\Auth\Table
{
    /**
     * @var Table
     */
    static $_instance;

    /**
     * Table
     *
     * @var string
     */
    protected $_table = 'users';

    protected $_identityColumn = 'login';

    protected $_credentialColumn = 'password';

    protected $_rowClass = '\Application\Users\Row';

    /**
     * Primary key(s)
     * @var array
     */
    protected $_primary = array('id');

    /**
     * Get row
     *
     * @param integer $id
     * @return \Bluz\Db\Row
     */
    public function getRow($id)
    {
        $rowset = $this->find($id);

        if ($rowset->count()) {
            return $rowset->current();
        }
        $this->insert(array('id' => $id));

        return $this->getRow($id);
    }

    /**
     * Fetch all users
     *
     * @return \ArrayObject
     */
    public function fetchAll()
    {
        $rowset = $this->_fetch("SELECT * FROM {$this->_table}", array());

        $modelset = array();
        foreach ($rowset as $row) {
            $modelset[] = new Model($row);
        }
        return new \ArrayObject($modelset, \ArrayObject::ARRAY_AS_PROPS);
    }
}
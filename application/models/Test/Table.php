<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

/**
 * Table
 *
 * @package  Application\Test
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'test';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * save test row
     *
     * @return null|string
     */
    public function saveTestRow()
    {
        return $this->insert(['name' => 'Example #' . rand(1, 10), 'email' => 'example@example.com']);
    }

    /**
     * update test row
     *
     * @return integer
     */
    public function updateTestRows()
    {
        return $this->update(['email' => 'example2@example.com'], ['email' => 'example@example.com']);
    }

    /**
     * delete test row
     *
     * @return integer
     */
    public function deleteTestRows()
    {
        return $this->delete(['email' => 'example2@example.com']);
    }
}

<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Proxy\Db;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * Crud based on Db\Table
 *
 * @package  Application\Test
 *
 * @method   Table getTable()
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * {@inheritdoc}
     *
     * @param int $offset
     * @param int $limit
     * @param array $params
     * @return array|int|mixed
     */
    public function readSet($offset = 0, $limit = 10, $params = array())
    {
        $select = Db::select('*')
            ->from('test', 't');

        if ($limit) {
            $selectPart = $select->getQueryPart('select');
            $selectPart[0] = 'SQL_CALC_FOUND_ROWS ' . current($selectPart);
            $select->select($selectPart);

            $select->setLimit($limit);
            $select->setOffset($offset);
        }

        $result = $select->execute('\\Application\\Test\\Row');

        if ($limit) {
            $total = Db::fetchOne('SELECT FOUND_ROWS()');
        } else {
            $total = sizeof($result);
        }

        if (sizeof($result) < $total && Request::METHOD_GET == Request::getMethod()) {
            Response::setStatusCode(206);
            Response::setHeader(
                'Content-Range',
                'items '.$offset.'-'.($offset+sizeof($result)).'/'. $total
            );
        }

        return $result;
    }
}

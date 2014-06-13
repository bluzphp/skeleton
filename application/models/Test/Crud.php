<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

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
        $select = app()->getDb()
            ->select('*')
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
            $total = app()->getDb()->fetchOne('SELECT FOUND_ROWS()');
        } else {
            $total = sizeof($result);
        }

        if (sizeof($result) < $total) {
            app()->getResponse()
                ->setCode(206)
                ->setHeader('Content-Range', 'items '.$offset.'-'.($offset+sizeof($result)).'/'. $total);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $data
     * @return bool
     */
    public function validateCreate($data)
    {
        $validator = new ValidatorBuilder();

        // name validator
        $validator->add(
            'name',
            v::required()->latin()
        );

        // email validator
        $validator->add(
            'email',
            v::required()->email()
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function validateUpdate($id, $data)
    {
        $validator = new ValidatorBuilder();

        // name validator
        $validator->add(
            'name',
            v::notEmpty()->latin()
        );

        // email validator
        $validator->add(
            'email',
            v::notEmpty()->email()
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }
}

<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Crud\Table;

/**
 * Crud based on Db\Table
 *
 * @category Application
 * @package  Test
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends Table
{
    /**
     * {@inheritdoc}
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
            http_response_code(206);
            header('Content-Range: items '.$offset.'-'.($offset+sizeof($result)).'/'. $total);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        // name validator
        $name = isset($data['name'])?$data['name']:null;
        $this->checkName($name);

        // email validator
        $email = isset($data['email'])?$data['email']:null;
        $this->checkEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        // name validator
        if (isset($data['name'])) {
            $this->checkName($data['name']);
        }

        // email validator
        if (isset($data['email'])) {
            $this->checkEmail($data['email']);
        }
    }

    /**
     * checkName
     *
     * @param $name
     * @return void
     */
    protected function checkName($name)
    {
        if (empty($name)) {
            $this->addError('Name can\'t be empty', 'name');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $name)) {
            $this->addError('Name should contains only Latin characters', 'name');
        }
    }

    /**
     * checkEmail
     *
     * @param $email
     * @return void
     */
    protected function checkEmail($email)
    {
        if (empty($email)) {
            $this->addError('Email can\'t be empty', 'email');
        } elseif (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('Email has invalid format', 'email');
        }
    }
}

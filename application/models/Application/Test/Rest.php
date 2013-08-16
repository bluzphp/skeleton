<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Application\Exception\NotFoundException;
use Bluz\Rest\AbstractRest;

/**
 * Class Rest
 *
 * @package Application\Test
 */
class Rest extends AbstractRest
{
    /**
     * {@inheritdoc}
     */
    public function index(array $params = array())
    {
        $select = app()->getDb()
            ->select('*')
            ->from('test', 't');

        if (isset($params['page']) && isset($params['limit'])) {
            $selectPart = $select->getQueryPart('select');
            $selectPart[0] = 'SQL_CALC_FOUND_ROWS ' . current($selectPart);
            $select->select($selectPart);

            $select->setLimit($params['limit']);
            $select->setPage($params['page']);
        }

        $result = $select->execute('\\Application\\Test\\Row');

        if (isset($params['page']) && isset($params['limit'])) {
            $total = app()->getDb()->fetchOne('SELECT FOUND_ROWS()');
        } else {
            $total = sizeof($result);
        }

        header('Bluz-Rest-Total: '. $total);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return app()->getDb()
            ->select('*')
            ->from('test', 't')
            ->where('t.id = ?', $id)
            ->limit(1)
            ->execute('\\Application\\Test\\Row');
    }

    /**
     * {@inheritdoc}
     */
    public function post(array $data)
    {
        // TODO: validation here
        return app()->getDb()
            ->insert('test')
            ->setArray($data)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function put($id, array $data)
    {
        // TODO: validation here
        return app()->getDb()->update('test')
            ->setArray($data)
            ->where('id = ?', $id)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return app()->getDb()->delete('test')
            ->where('id = ?', $id)
            ->execute();
    }
}

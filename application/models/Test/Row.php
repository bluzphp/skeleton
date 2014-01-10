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
 * Test Row
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $status enum('active','disable','delete')
 *
 * @category Application
 * @package  Test
 */
class Row extends \Bluz\Db\Row
{
}

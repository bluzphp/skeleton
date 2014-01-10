<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Media;

/**
 * Table
 *
 * @category Application
 * @package  Media
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');
}

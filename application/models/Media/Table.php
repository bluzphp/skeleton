<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Media;

use Application\Exception;
use Bluz\Proxy\Auth;

/**
 * Table
 *
 * @package  Application\Media
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
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

    /**
     * Get images of current user
     *
     * @return Row[]
     * @throws Exception
     */
    public function getImages()
    {
        /** @var Row $user */
        if (!$user = Auth::getIdentity()) {
            throw new Exception('User not found');
        }

        return $this->getImagesByUserId($user->id);
    }

    /**
     * Get images by owner
     *
     * @param  integer $id
     * @return Row[]
     */
    public function getImagesByUserId($id)
    {
        return self::select()
            ->where('type LIKE (?)', 'image/%')
            ->andWhere('userId = ?', $id)
            ->execute();
    }
}

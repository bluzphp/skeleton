<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Options;

/**
 * Class Table
 *
 * @package Application\Options
 *
 * @method static Row create(array $data = [])
 * @method static Row findRow($primaryKey)
 * @method static Row findRowWhere($whereList)
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Default namespace for all keys
     */
    const NAMESPACE_DEFAULT = 'default';

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'options';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('namespace', 'key');

    /**
     * Get option value for use it from any application place
     *
     * @param string $key
     * @param string $namespace
     * @return mixed
     */
    public static function get($key, $namespace = self::NAMESPACE_DEFAULT)
    {
        /**
         * @var \Application\Options\Row $row
         */
        if ($row = self::findRowWhere(['key' => $key, 'namespace' => $namespace])) {
            return $row->value;
        }
        return null;
    }

    /**
     * Set option value for use it from any application place
     *
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @return mixed
     */
    public static function set($key, $value, $namespace = self::NAMESPACE_DEFAULT)
    {
        /**
         * @var \Application\Options\Row $row
         */
        $row = self::findRowWhere(['key' => $key, 'namespace' => $namespace]);
        if (!$row) {
            $row = self::create();
            $row->key = $key;
            $row->value = $value;
            $row->namespace = $namespace;
        }
        $row->value = $value;
        return $row->save();
    }

    /**
     * Remove option
     *
     * @param string $key
     * @param string $namespace
     * @return integer
     */
    public static function remove($key, $namespace = self::NAMESPACE_DEFAULT)
    {
        return self::delete(['key' => $key, 'namespace' => $namespace]);
    }
}

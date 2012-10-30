<?php
namespace Application\Pages;

/**
 * Pages Row
 *
 * @category Application
 * @package  Pages
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $alias;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $keywords;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $created;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var integer
     */
    public $userId;


    /**
     * __insert
     *
     * @return void
     */
    public function preInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
        if ($user = $this->getApplication()->getAuth()->getIdentity()) {
            $this->userId = $user->id;
        } else {
            $this->userId = \Application\Users\Row::SYSTEM_USER;
        }
    }

    /**
     * __update
     *
     * @return void
     */
    public function preUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}

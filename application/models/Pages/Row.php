<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

use Application\Users;
use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

/**
 * Pages Row
 *
 * @package  Application\Pages
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $keywords
 * @property string $description
 * @property string $created
 * @property string $updated
 * @property integer $userId
 *
 * @SWG\Model(id="Pages")
 * @SWG\Property(name="id", type="integer")
 * @SWG\Property(name="title", type="string", required=true)
 * @SWG\Property(name="alias", type="string", required=true)
 * @SWG\Property(name="content", type="string", required=true)
 * @SWG\Property(name="keywords", type="string", description="Meta keywords")
 * @SWG\Property(name="description", type="string", description="Meta description")
 * @SWG\Property(name="created", type="string", format="date-time")
 * @SWG\Property(name="updated", type="string", format="date-time")
 * @SWG\Property(name="userId", type="integer")
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function beforeSave()
    {
        // title validator
        $this->addValidator(
            'title',
            v::required()
        );

        // alias validator
        $this->addValidator(
            'alias',
            v::required(),
            v::slug(),
            v::callback(function ($input) {
                if ($row = $this->getTable()->findRowWhere(['alias' => $input])) {
                    if ($row->id != $this->id) {
                        return false;
                    }
                }
                return true;
            })->setError('Alias "{{input}}" already exists')
        );

        // content validator
            $this->addValidator(
                'content',
                v::callback(function ($input) {
                    if (empty($input) or trim(strip_tags($input, '<img>')) == '') {
                        return false;
                    }
                    return true;
                })->setError('Content can\'t be empty')
            );
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');

        /* @var \Application\Users\Row $user */
        if ($user = app()->user()) {
            $this->userId = $user->id;
        } else {
            $this->userId = Users\Table::SYSTEM_USER;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}

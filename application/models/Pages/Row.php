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
use Bluz\Proxy\Auth;
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
 * @SWG\Definition(definition="pages", title="page", required={"id", "title", "alias", "content"})
 * @SWG\Property(property="id", type="integer", description="Page UID", example=42)
 * @SWG\Property(property="title", type="string", description="Page title",
 *     example="The Ultimate Question of Life")
 * @SWG\Property(property="alias", type="string", description="SEO URL",
 *     example="the-ultimate-question")
 * @SWG\Property(property="content", type="string", description="Text",
 *     example="The Ultimate Question of Life, the Universe, and Everything")
 * @SWG\Property(property="keywords", type="string", description="Meta keywords",
 *     example="42, life, universe, everything")
 * @SWG\Property(property="description", type="string", description="Meta description",
 *     example="The Hitchhiker's Guide to the Galaxy")
 * @SWG\Property(property="created", type="string", format="date-time", example="2017-03-17 19:06:28")
 * @SWG\Property(property="updated", type="string", format="date-time", example="2017-03-17 19:06:28")
 * @SWG\Property(property="userId", type="integer", description="Author", example=2)
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
        if ($user = Auth::getIdentity()) {
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

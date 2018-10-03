<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Pages;

use Application\Users;
use Bluz\Proxy\Auth;
use Bluz\Validator\Traits\Validator;

/**
 * Pages Row
 *
 * @package  Application\Pages
 *
 * @property integer $id
 * @property string  $title
 * @property string  $alias
 * @property string  $content
 * @property string  $keywords
 * @property string  $description
 * @property string  $created
 * @property string  $updated
 * @property integer $userId
 *
 * @OA\Schema(schema="page", title="page", required={"id", "title", "alias", "content"})
 * @OA\Property(property="id", type="integer", description="Page UID",
 *     example=42)
 * @OA\Property(property="title", type="string", description="Page title",
 *     example="The Ultimate Question of Life")
 * @OA\Property(property="alias", type="string", description="SEO URL",
 *     example="the-ultimate-question")
 * @OA\Property(property="content", type="string", description="Text",
 *     example="The Ultimate Question of Life, the Universe, and Everything")
 * @OA\Property(property="keywords", type="string", description="Meta keywords",
 *     example="42, life, universe, everything")
 * @OA\Property(property="description", type="string", description="Meta description",
 *     example="The Hitchhiker's Guide to the Galaxy")
 * @OA\Property(property="created", type="string", format="date-time", description="Created date",
 *     example="2017-03-17 19:06:28")
 * @OA\Property(property="updated", type="string", format="date-time", description="Last updated date",
 *     example="2017-03-17 19:06:28")
 * @OA\Property(property="userId", type="integer", description="Author ID",
 *     example=2)
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * {@inheritdoc}
     *
     * @throws \Bluz\Validator\Exception\ComponentException
     */
    public function beforeSave(): void
    {
        // title validator
        $this->addValidator('title')
            ->required()
        ;

        // alias validator
        $this->addValidator('alias')
            ->required()
            ->slug()
            ->callback(
                function ($input) {
                    if ($row = $this->getTable()::findRowWhere(['alias' => $input])) {
                        if ($row->id != $this->id) {
                            return false;
                        }
                    }
                    return true;
                },
                __('This alias already exists')
            )
        ;

        // content validator
        $this->addValidator('content')
            ->callback(
                function ($input) {
                    return !(empty($input) or trim(strip_tags($input, '<img>')) === '');
                },
                __('Content can\'t be empty')
            )
        ;

        $this->addValidator('keywords')
            ->alphaNumeric(', ')
        ;

        $this->addValidator('description')
            ->alphaNumeric(', ')
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function beforeInsert(): void
    {
        $this->created = gmdate('Y-m-d H:i:s');

        /* @var \Application\Users\Row $user */
        if ($user = Auth::getIdentity()) {
            $this->userId = $user->getId();
        } else {
            $this->userId = Users\Table::SYSTEM_USER;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function beforeUpdate(): void
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}

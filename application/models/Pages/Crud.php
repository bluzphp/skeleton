<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

/**
 * Class Crud of Pages
 * @package  Application\Pages
 **
 * @method   Table getTable()
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * {@inheritdoc}
     */
    public function validate($primary, $data)
    {
        $validator = new ValidatorBuilder();

        // title validator
        $validator->add(
            'title',
            v::required()
        );

        // alias validator
        $validator->add(
            'alias',
            v::required(),
            v::slug(),
            v::callback(function ($input) use ($data) {
                if ($row = $this->getTable()->findRowWhere(['alias' => $input])) {
                    if ($row->id != $data['id']) {
                        return false;
                    }
                }
                return true;
            })->setError('Alias "{{input}}" already exists')
        );

        // content validator
        $validator->add(
            'content',
            v::callback(function ($input) {
                if (empty($input) or trim(strip_tags($input, '<img>')) == '') {
                    return false;
                }
                return true;
            })->setError('Content can\'t be empty')
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }
}

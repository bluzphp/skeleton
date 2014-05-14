<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

/**
 * @category Application
 * @package  Pages
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
        // name validator
        $title = isset($data['title'])?$data['title']:null;
        if (empty($title)) {
            $this->addError('Title can\'t be empty', 'title');
        }

        // alias update
        $alias = isset($data['alias'])?$data['alias']:null;
        if (empty($alias)) {
            $this->addError('Alias can\'t be empty', 'alias');
        } elseif (!preg_match('/^[a-zA-Z0-9_\.\-]+$/i', $alias)) {
            $this->addError('Alias should contains only Latin characters, dots and dashes', 'alias');
        } elseif ($row = $this->getTable()->findRowWhere(['alias' => $alias])) {
            if ($row->id != $data['id']) {
                $this->addError(
                    __('Alias "%s" already exists', esc($alias)),
                    'alias'
                );
            }
        }

        // content validator
        $content = isset($data['content'])?$data['content']:null;
        if (empty($content) or trim(strip_tags($content, '<img>')) == '') {
            $this->addError('Content can\'t be empty', 'content');
        }
    }
}

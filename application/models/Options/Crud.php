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
 * Crud
 *
 * @category Application
 * @package  Options
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * {@inheritdoc}
     */
    public function validate($id, $data)
    {
        // key name validator
        $key = isset($data['key'])?$data['key']:null;
        if (empty($key)) {
            $this->addError('Key name can\'t be empty', 'key');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $key)) {
            $this->addError('Key name should contains only Latin characters', 'key');
        }

        // namespace validator
        $namespace = isset($data['namespace'])?$data['namespace']:null;
        if (empty($namespace)) {
            $this->addError('Name can\'t be empty', 'namespace');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $namespace)) {
            $this->addError('Name should contains only Latin characters', 'namespace');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        $key = isset($data['key'])?$data['key']:null;
        $namespace = isset($data['namespace'])?$data['namespace']:null;
        // unique validator
        if ($row = $this->getTable()->findRowWhere(['key' => $key, 'namespace' => $namespace])) {
            $this->addError(
                __('Key name "%s" already exists in namespace "%s"', esc($key), esc($namespace)),
                'key'
            );
        }
    }
}

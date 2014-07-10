<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

/**
 * Test Row
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $status enum('active','disable','delete')
 *
 * @category Application
 * @package  Test
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * Before Insert/Update
     * @return void
     */
    protected function beforeSave()
    {
        // name validator
        $this->addValidator(
            'name',
            v::required()->latin()
        );

        // email validator
        $this->addValidator(
            'email',
            v::required()->email()
        );
    }
}

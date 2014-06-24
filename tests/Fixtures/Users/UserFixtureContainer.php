<?php
/**
 * @namespace
 */
namespace Application\Tests\Fixtures\Users;


/**
 * User
 *
 * @package  Application\Tests
 *
 * @author   Taras Seryogin
 * @created  12.06.14 17.11
 */
class UserFixtureContainer
{
    /**
     * User fixture for test
     * @var array
     */
    public static $fixture = array(
        'id' => 1,
        'login' => 'admin',
        'email' => null,
        'created' => '2012-11-09 07:38:41',
        'updated' => '2014-06-04 11:51:07',
        'status' => 'active'
    );
}

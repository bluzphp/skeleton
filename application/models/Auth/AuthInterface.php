<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 06.05.15
 * Time: 18:41
 */
namespace Application\Auth;

/**
 * Interface AuthInterface
 * @package Application\Auth
 */
interface AuthInterface
{
    /**
     * @param array $data
     * @param \Application\Users\Row $user
     * @return void
     */
    public function registration($data, $user);

    /**
     * @return void
     */
    public function authProcess();

    /**
     * @return array
     * @throws \Application\Exception
     */
    public function getOptions();

    /**
     * @param \Application\Auth\Row $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic($auth);

    /**
     * @return array
     */
    public function getProfile();
}

<?php

namespace Application\Auth;

interface AuthInterface{

    /**
     * @param array $data
     * @param \Application\Users\Entity\User $user
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
     * @return void
     */
    public function redirectLogic();

    /**
     * @param Auth $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic(Auth $auth);

    /**
     * @return array
     */
    public function getProfile();

    /**
     * @param string $provider
     * @return mixed
     */
    public function setProvider($provider);

    /**
     * @return mixed
     */
    public function getProvider();

}
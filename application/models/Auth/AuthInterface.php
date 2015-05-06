<?php

namespace Application\Auth;

interface AuthInterface{

    /**
     * @param \Hybrid_User_Profile $data
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
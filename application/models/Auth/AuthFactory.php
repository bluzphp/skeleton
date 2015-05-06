<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 3/5/15
 * Time: 1:35 PM
 */

namespace Application\Auth;

use Application\Exception;
use Bluz\Proxy\Config;
use Bluz\Proxy\Request;
use Google\Client;

class AuthFactory extends AbstractAuth
{
    /** @var AbstractAuth $authType*/
    protected $authType;

    /**
     * @param string $provider
     * @throws \Application\Exception
     */
    public function setProvider($provider){

      /*  $options = Config::getData('hybridauth','providers');
        $provider = $options[ucfirst($provider)]['provider'];

        if(empty($provider)){
            throw new Exception('No provider was found !');
        }
        $className = $provider;
        if (!class_exists($className)) {
            throw new Exception(sprintf('Class with name %s not found !', $className));
        }
        $this->authType = new $className();*/
    }


    /**
     * @param \Bluz\Http\Response $response
     */
    public function setResponse($response)
    {
        $this->authType->setResponse($response);
    }


    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->authType->setIdentity($identity);
    }

    /**
     * delegate to type
     */
    public function authProcess(){

        $this->authType->authProcess();
    }

    public function getProvider(){

        return $this->authType;
    }
}
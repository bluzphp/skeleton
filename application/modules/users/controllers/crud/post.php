<?php

/**
 * CRUD controller for POST method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Application\Users\Mail;
use Application\Users\Table;
use Bluz\Common\Exception\CommonException;
use Bluz\Common\Exception\ComponentException;
use Bluz\Controller\ControllerException;
use Bluz\Db\Exception\DbException;
use Bluz\Db\Exception\InvalidPrimaryKeyException;
use Bluz\Db\Exception\TableNotFoundException;
use Bluz\Http\Exception\ForbiddenException;
use Bluz\Http\Exception\NotAcceptableException;
use Bluz\Http\Exception\NotAllowedException;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Http\RequestMethod;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method POST
 *
 * @param \Bluz\Crud\Table $crud
 * @param mixed $primary
 * @param array $data
 *
 * @return array
 * @throws Exception
 * @throws CommonException
 * @throws ComponentException
 * @throws ControllerException
 * @throws DbException
 * @throws InvalidPrimaryKeyException
 * @throws TableNotFoundException
 * @throws ForbiddenException
 * @throws NotAcceptableException
 * @throws NotAllowedException
 * @throws NotFoundException
 */
return function ($crud, $primary, $data) {
    try {
        // unset id
        unset($data['id']);

        // password
        $password = $data['password'] ?? null;
        $password2 = $data['password2'] ?? null;

        if (empty($password)) {
            $exception = new ValidatorException();
            $exception->setErrors(['password' => __('Password can\'t be empty')]);
            throw $exception;
        }

        if ($password !== $password2) {
            $exception = new ValidatorException();
            $exception->setErrors(['password2' => __('Password is not equal')]);
            throw $exception;
        }

        unset($data['password2']);

        // status
        $data['status'] = Table::STATUS_PENDING;

        // Result is Primary Key(s)
        $result = $crud->createOne($data);

        $user = Table::findRow($result);

        // create auth
        Auth\Provider\Equals::create($user, $password);

        // send email
        if (Mail::activation($user, $password)) {
            Messages::addSuccess(
                'Your account has been created and an activation link has ' .
                'been sent to the e-mail address you entered.<br/>' .
                'Note that you must activate the account by clicking on the activation link ' .
                'when you get the e-mail before you can login.'
            );
        } else {
            Messages::addError('Unable to send email. Please contact administrator');
        }

        return [
            'row' => $user,
            'method' => RequestMethod::PUT
        ];
    } catch (ValidatorException $e) {
        $row = $crud->readOne(null);
        $row->setFromArray($data);

        return [
            'row' => $row,
            'errors' => $e->getErrors(),
            'method' => Request::getMethod()
        ];
    }
};

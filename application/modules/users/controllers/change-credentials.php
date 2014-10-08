<?php
/**
 * Change credentials controller
 * Allowed only for those who registered via social networks
 *
 * @author  Dmitriy Rassadkin <volkov.sergey@nixsolutions.com>
 * @created 02.10.2014 11:45
 */
namespace Application;

/**
 * @privilege EditCredentials
 */
use Bluz\Validator\Exception\ValidatorException;
use Bluz\Controller;

return

    function () {
        /**
         * @var Bootstrap $this
         */
        //set email and password for those who registered by social networks
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->useLayout('small.phtml');
        }

        if ($this->getRequest()->isPost()) {
            try {
                if (!$login = $this->getRequest()->getParam('login')) {
                    throw ValidatorException::exception('login', __('Login can\'t be empty'));
                }

                if (!$email = $this->getRequest()->getParam('email')) {
                    throw ValidatorException::exception('email', __('Email can\'t be empty'));
                }

                if (!$password = $this->getRequest()->getParam('password')) {
                    throw ValidatorException::exception('password', __('Password can\'t be empty'));
                }

                if ($password !== $this->getRequest()->getParam('password2')) {
                    throw ValidatorException::exception('password2', 'Passwords aren\'t equal');
                }

                //update user info
                $row = Users\Table::findRow($this->user()->id);
                $row->login = $login;
                $row->email = $email;
                $row->addValidation();
                $row->save();

                //set password
                $authTable = Auth\Table::getInstance();
                $authTable->generateEquals($row, $password);

                //change role to member
                $memberRow = Roles\Table::findRowWhere(['name' => Roles\Table::BASIC_MEMBER]);
                $socialRow = Roles\Table::findRowWhere(['name' => Roles\Table::BASIC_SOCIAL]);
                UsersRoles\Table::update(
                    ['roleId' => $memberRow->id],
                    ['userId' => $this->user()->id, 'roleId' => $socialRow->id]
                );
                $row->login();

                $this->getMessages()->addSuccess("Your account was successfully updated");
                $this->redirectTo('index', 'index');
            } catch (ValidatorException $e) {
                return ['errors' => $e->getErrors()];
            }
        }
    };

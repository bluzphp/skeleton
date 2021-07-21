<?php

/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Http\Exception\RedirectException;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Db;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;
use Psr\Cache\InvalidArgumentException;

/**
 * @privilege Management
 *
 * @param array $acl
 *
 * @return void
 * @throws RedirectException
 * @throws InvalidArgumentException
 */
return function ($acl) {
    /**
     * @var Controller $this
     */
    $callback = function () use ($acl) {
        /**
         * @var Controller $this
         */
        Db::query('DELETE FROM acl_privileges');

        foreach ($acl as $roleId => $modules) {
            foreach ($modules as $module => $privileges) {
                foreach ($privileges as $privilege => $flag) {
                    Db::query(
                        'INSERT INTO acl_privileges SET roleId = ?, module = ?, privilege = ?',
                        [$roleId, $module, $privilege]
                    );
                }
            }
        }
    };

    if (empty($acl)) {
        Messages::addError('Privileges set is empty. You can\'t remove all of them');
    } elseif (Db::transaction($callback)) {
        if (!Cache::clearTags(['privileges'])) {
            Cache::clear();
        }
        Messages::addSuccess('All data was saved');
    } else {
        Messages::addError('Internal Server Error');
    }

    Response::redirectTo('acl', 'index');
};

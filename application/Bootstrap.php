<?php

/**
 * Copyright (c) 2012 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application;
use Application\Exception;

/**
 * Bootstrap
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 17:38
 */
class Bootstrap extends Application
{
    /**
     * initial environment
     *
     * @param string $environment
     * @return \Bluz\Application
     */
    public function init($environment = ENVIRONMENT_PRODUCTION)
    {
        // Profiler hooks
        if (constant('DEBUG')) {
            $this->getEventManager()->attach('log', function($event){
                /* @var \Bluz\EventManager\Event $event */
                \Bluz\Profiler::log($event->getTarget());
            });
            $this->getEventManager()->attach('layout:header', function($event){
                /* @var \Bluz\EventManager\Event $event */
                \Bluz\Profiler::log('layout:header');
            });
            $this->getEventManager()->attach('layout:content', function($event){
                /* @var \Bluz\EventManager\Event $event */
                \Bluz\Profiler::log('layout:content');
            });
            $this->getEventManager()->attach('layout:footer', function($event){
                /* @var \Bluz\EventManager\Event $event */
                \Bluz\Profiler::log('layout:footer');

                $version = null;
                $comment = null;

                /*$version = shell_exec('hg tip --style compact');
                if ($version) {
                    list($version, $comment) = explode("\n", $version, 2);
                    $comment = trim($comment);
                }*/

                ?>
                    <section class="debug-panel">
                        <section class="debug-panel-header">
                            <h3 class="debug-panel-title">
                                Debug Panel
                                <span class="badge pull-right"><?php printf("%f :: %s kb", microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], ceil((memory_get_usage()/1024)))?></span>
                            </h3>
                            <?php if ($version) :?>
                            <code class="debug-panel-version">
                                <?= $this->getLayout()->ahref(
                                        $version, ['index', 'changelog', array()],
                                        array('title' => $comment)
                                    )
                                ?>
                            </code>
                            <?php endif ?>
                        </section>
                        <section class="debug-panel-content">
                            <pre><?php print_r(\Bluz\Profiler::data());?></pre>
                        </section>
                    </section>
                <?php
            });
        }

        // dispatch hook for acl realization
        $this->getEventManager()->attach('dispatch', function($event) {
            $eventParams = $event->getParams();
            \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
        });

        // widget hook for acl realization
        $this->getEventManager()->attach('widget', function($event) {
            $eventParams = $event->getParams();
            \Bluz\Profiler::log('bootstrap:widget: '.$eventParams['module'].'/'.$eventParams['widget']);
        });


        $res = parent::init($environment);

        $this->getLayout()->title("Dark Side");

        return $res;
    }

    /**
     * getRcl
     *
     * @return \Bluz\Rcl\Rcl
     */
    public function getRcl()
    {
        if (!$this->rcl) {
            $this->rcl = parent::getRcl();
            //$this->rcl->addAssertion(\Application\UserToResourceToPrivilege\Table::getInstance());
        }
        return $this->rcl;
    }
}

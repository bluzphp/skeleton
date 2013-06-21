<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
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
use Bluz\EventManager\Event;
use Bluz\EventManager\EventManager;

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
     * @return self
     */
    public function init($environment = 'production')
    {
        // Profiler hooks
        if (constant('DEBUG') && DEBUG) {
            $this->getEventManager()->attach('view', function($event){
                /* @var \Bluz\EventManager\Event $event */
                $this->log('view');
            });
            $this->getEventManager()->attach('layout:header', function($event){
                /* @var \Bluz\View\Layout $layout */
                /* @var \Bluz\EventManager\Event $event */
                $layout = $event->getParam('layout');

                // add debug.css
                echo $layout->style('/css/debug.css');

                /* @var \Bluz\EventManager\Event $event */
                $this->log('layout:header');
            });
            $this->getEventManager()->attach('layout:content', function($event){
                /* @var \Bluz\EventManager\Event $event */
                $this->log('layout:content');
            });
            $this->getEventManager()->attach('layout:footer', function($event){
                /* @var \Bluz\EventManager\Event $event */
                $this->log('layout:footer');

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
                            <pre><?php print_r($this->getLogger()->get('info'));?></pre>
                        </section>
                    </section>
                <?php
            });
        }

        // dispatch hook for acl realization
        $this->getEventManager()->attach('dispatch', function($event) {
            /* @var \Bluz\EventManager\Event $event */
            $eventParams = $event->getParams();
            $this->log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
        });

        // widget hook for acl realization
        $this->getEventManager()->attach('widget', function($event) {
            /* @var \Bluz\EventManager\Event $event */
            $eventParams = $event->getParams();
            $this->log('bootstrap:widget: '.$eventParams['module'].'/'.$eventParams['widget']);
        });


        $res = parent::init($environment);

        // example of setup Layout
        $this->getLayout()->title("Bluz Skeleton");

        return $res;
    }

    /**
     * denied
     *
     * @throws Exception
     * @return void
     */
    public function denied()
    {
        // save URL to session
        $this->getSession()->rollback = $this->getRequest()->getRequestUri();

        // process AJAX request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->getMessages()->addError('You don\'t have permissions, please sign in');
        }
        // redirect to login page
        if (!$this->getAuth()->getIdentity()) {
            $this->redirectTo('users', 'signin');
        }
        throw new Exception('You don\'t have permission to access this page', 403);
    }

    /**
     * @return Application
     */
    public function finish()
    {
        if ($messages = $this->getLogger()->get('error')) {
            errorLog(join("\n", $messages)."\n");
        }
        return $this;
    }
}
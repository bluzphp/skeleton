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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Bluz\Application;
use Bluz\Exception;

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
     * @param $environment
     * @return Bootstrap
     */
    public function init($environment = ENVIRONMENT_PRODUCTION)
    {
        // Profiler hooks
        if (defined('DEBUG') && DEBUG) {
            $this->getEventManager()->attach('log', function($event){
                \Bluz\Profiler::log($event->getTarget());
            });
            $this->getEventManager()->attach('layout:header', function($event){
                \Bluz\Profiler::log('layout:header');
            });
            $this->getEventManager()->attach('layout:content', function($event){
                \Bluz\Profiler::log('layout:content');
            });
            $this->getEventManager()->attach('layout:footer', function($event){
                \Bluz\Profiler::log('layout:footer');
                echo '<pre>';
                print_r(Bluz\Profiler::data());
                echo '</pre>';
            });
        }

        return parent::init($environment);
    }
}

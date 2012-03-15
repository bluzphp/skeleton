<?php
/**
 * Error controller test
 * 
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
namespace Application;

class ErrorTest extends TestCase
{
    /**
     * testError404
     * 
     * @return void
     */
    public function testError403()
    {
        $view = $this->_app->dispatch('error', 'error', array('code'=>403));
        $this->assertEquals($view->title, 'Error 403');
    }

    /**
     * testError404
     *
     * @return void
     */
    public function testError404()
    {
        $view = $this->_app->dispatch('error', 'error', array('code'=>404));
        $this->assertEquals($view->title, 'Error 404');
    }
}

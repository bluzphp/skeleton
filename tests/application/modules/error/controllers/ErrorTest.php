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
        $this->_app->dispatch('error', 'error', array('code'=>403));
        $this->assertEquals($this->_app->getLayout()->title, 'Error 403');
    }

    /**
     * testError404
     *
     * @return void
     */
    public function testError404()
    {
        $this->_app->dispatch('error', 'error', array('code'=>404));
        $this->assertEquals($this->_app->getLayout()->title, 'Error 404');
    }
}

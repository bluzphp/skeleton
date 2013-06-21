<?php
/**
 * Error controller test
 * 
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
namespace Application\Tests;

class ErrorTest extends TestCase
{
    /**
     * testError404
     * 
     * @return void
     */
    public function testError403()
    {
        $this->app->dispatch('error', 'index', ['message'=>'Error', 'code'=>403]);
        $this->assertEquals($this->app->getLayout()->title(), '403 Forbidden');
    }

    /**
     * testError404
     *
     * @return void
     */
    public function testError404()
    {
        $this->app->dispatch('error', 'index', ['message'=>'Error', 'code'=>404]);
        $this->assertEquals($this->app->getLayout()->title(), '404 Not Found');
    }
}

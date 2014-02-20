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
        $response = $this->app->dispatch('error', 'index', ['message'=>'Error', 'code'=>403]);
        $this->assertEquals($response->title, 'Forbidden');
    }

    /**
     * testError404
     *
     * @return void
     */
    public function testError404()
    {
        $response = $this->app->dispatch('error', 'index', ['message'=>'Error', 'code'=>404]);
        $this->assertEquals($response->title, 'Not Found');
    }
}

<?php
/**
 * Error controller test
 * 
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
namespace Application\Tests;

class IndexTest extends TestCase
{
    /**
     * @expectedException \Bluz\Application\Exception\NotFoundException
     */
    public function testError404()
    {
        $this->app->dispatch('pages', 'index', ['alias' => uniqid('random_name_')]);
    }
}

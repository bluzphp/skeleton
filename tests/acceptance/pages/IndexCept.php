<?php 
// @group pages
$I = new AcceptanceTester($scenario);
$I->wantTo('open static page');
$I->setHeader("Accept", "text/html");
$I->amOnPage('/about.html');
$I->see('About Bluz Framework');

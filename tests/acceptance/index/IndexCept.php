<?php
// @group test
$I = new AcceptanceTester($scenario);
$I->wantTo('open homepage');
$I->setHeader("Accept", "text/html");
$I->amOnPage('/');
$I->see('Home');
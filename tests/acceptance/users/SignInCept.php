<?php
// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('login as admin');
$I->setHeader("Accept", "text/html");
$I->amOnPage('/users/signin');
$I->fillField('login', 'admin');
$I->fillField('password', 'admin');
$I->click('signin');
$I->see('You are signed');
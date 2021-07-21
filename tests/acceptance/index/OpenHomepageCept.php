<?php

// @group index
$I = new AcceptanceTester($scenario);
$I->wantTo('open "Homepage"');
$I->setHeader('Accept', 'text/html');
$I->amOnPage('/');
$I->see('Home');

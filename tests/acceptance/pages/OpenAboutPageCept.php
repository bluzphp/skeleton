<?php

// @group pages
$I = new AcceptanceTester($scenario);
$I->wantTo('open "About" page');
$I->setHeader('Accept', 'text/html');
$I->amOnPage('/about.html');
$I->see('About');

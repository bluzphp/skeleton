<?php

// @group pages
$I = new AcceptanceTester($scenario);
$I->wantTo('open "Terms and conditions" page');
$I->setHeader('Accept', 'text/html');
$I->amOnPage('/terms-and-conditions.html');
$I->see('Terms and conditions');

<?php 
// @group pages
$I = new AcceptanceTester($scenario);
$I->wantTo('open "Legal notices" page');
$I->setHeader('Accept', 'text/html');
$I->amOnPage('/legal-notices.html');
$I->see('Legal notices');

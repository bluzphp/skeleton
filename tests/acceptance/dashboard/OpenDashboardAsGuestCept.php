<?php
// @group dashboard
$I = new AcceptanceTester($scenario);
$I->wantTo('open dashboard as guest');
$I->amOnPage('/dashboard');
$I->see('Please sign in');

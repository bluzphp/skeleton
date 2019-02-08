<?php
// @group dashboard
$I = new AcceptanceTester($scenario);
$I->wantTo('open dashboard as administrator');
$I->amAdmin();
$I->amOnPage('/dashboard');
$I->see('Users');

<?php
// @group dashboard
$I = new AcceptanceTester($scenario);
$I->wantTo('open dashboard as member');
$I->amMember();
$I->amOnPage('/dashboard');
$I->see('Forbidden');

<?php
// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('sign in as administrator');
$I->amAdmin();
$I->see('You are signed');

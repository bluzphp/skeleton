<?php
// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('login as admin');
$I->amAdmin();
$I->see('You are signed');
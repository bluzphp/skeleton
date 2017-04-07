<?php
// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('see admin profile');
$I->setHeader("Accept", "text/html");
$I->amAdmin();
$I->amOnPage('/users/profile?id=2');
$I->see('Profile');
$I->see('admin');
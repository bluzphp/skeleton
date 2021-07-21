<?php

// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('see profile of "system"');
$I->setHeader('Accept', 'text/html');
$I->amAdmin();
$I->amOnPage('/users/profile/id/1'); // 1 = system, 2 = admin, 3 = member
$I->see('Profile');
$I->see('system');

<?php

// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('see profile of "administrator"');
$I->setHeader('Accept', 'text/html');
$I->amMember();
$I->amOnPage('/users/profile/id/2'); // 1 = system, 2 = admin, 3 = member
$I->see('Profile');
$I->see('admin');

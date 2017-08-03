<?php
// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('open ACL dashboard as administrator');
$I->amAdmin();
$I->amOnPage('/acl');
$I->see('Create role');

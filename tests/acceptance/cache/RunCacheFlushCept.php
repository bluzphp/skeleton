<?php

// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('trying to run command `cache/flush` as administrator');
$I->amAdmin();
$I->amOnPage('/cache/flush');
$I->seeNoticeHeader('Cache is disabled');

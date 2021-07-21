<?php

// @group users
$I = new AcceptanceTester($scenario);
$I->wantTo('trying to run command `cache/clean` as administrator');
$I->amAdmin();
$I->sendAjaxPostRequest('/cache/clean');
$I->seeNoticeHeader('Cache is disabled');

<?php
// @group test
$I = new ApiTester($scenario);
$I->wantTo('get all pages over REST API');
$I->haveHttpHeader('Accept', 'application/json');
$I->sendGET('pages/rest');
$I->seeResponseCodeIs(206);
$I->seeResponseContainsJson(['id' => '1']);


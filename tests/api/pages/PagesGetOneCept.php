<?php
// @group test
$I = new ApiTester($scenario);
$I->wantTo('get page by ID over REST API');
$I->haveHttpHeader('Accept', 'application/json');
$I->sendGET('pages/rest/1');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson(['id' => '1']);


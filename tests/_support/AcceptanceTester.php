<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * @param string $name
     * @param string $password
     */
    public function login($name, $password)
    {
        $I = $this;
        // logging in
        $I->setHeader("Accept", "text/html");
        $I->amOnPage('/users/signin');
        $I->fillField('login', $name);
        $I->fillField('password', $password);
        $I->click('signin');
    }

    /**
     * I'm admin
     *
     * @return void
     */
    public function amAdmin()
    {
        $this->login('admin', 'admin');
    }
}

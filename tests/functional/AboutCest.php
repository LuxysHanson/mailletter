<?php

class AboutCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('/site/about');
    }

    public function openAboutPage(FunctionalTester $I)
    {
        $I->see('About');
        $I->see('This is the About page. You may modify the following file to customize its content: ');
    }

}

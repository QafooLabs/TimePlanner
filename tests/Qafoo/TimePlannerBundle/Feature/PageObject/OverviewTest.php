<?php

namespace Qafoo\UserBundle\Feature\PageObject;

use Qafoo\FeatureTest\UserHelper;
use Qafoo\FeatureTest;
use Qafoo\Page;

/**
 * @group feature
 */
class OverviewTest extends FeatureTest
{
    use UserHelper;

    public function setUp()
    {
        $this->start();
    }

    public function testRedirectAnonymousUser()
    {
        $page = (new Page\Overview($this->session))->visit(Page\Overview::PATH);

        $this->assertInstanceOf(Page\Login::class, $page);
    }

    public function testShowOverviewWhenLoggedIn()
    {
        $this->loginUser('kore');
        $page = (new Page\Overview($this->session))->visit(Page\Overview::PATH);

        $this->assertInstanceOf(Page\Overview::class, $page);
    }

    public function testShowRemainingVacationDays()
    {
        $this->loginUser('kore');
        $page = (new Page\Overview($this->session))->visit(Page\Overview::PATH);

        $this->assertSame(30, $page->getVacationDays());
    }
}

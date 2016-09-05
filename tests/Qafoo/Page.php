<?php

namespace Qafoo;

use Behat\Mink\Session;

abstract class Page
{
    protected $session;

    protected $document;

    private $pageRegistry = array(
        Page\Login::PATH => Page\Login::class,
        Page\Overview::PATH => Page\Overview::class,
    );

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->document = $this->session->getPage();
    }

    protected function visitPath($path)
    {
        $domain = getenv('SERVER') ?: 'http://localhost:8888';
        $this->session->visit($domain . $path);
        return $this->session->getPage();
    }

    public function visit($path)
    {
        $this->visitPath($path);
        return $this->createFromDocument();
    }

    protected function find($cssSelector, $contextElement = null)
    {
        $contextElement = $contextElement ?: $this->document;

        $element = $contextElement->find('css', $cssSelector);
        \PHPUnit_FrameWork_Assert::assertNotNull($element, "Element $cssSelector not found");
        return $element;
    }

    protected function findAll($cssSelector, $contextElement = null)
    {
        $contextElement = $contextElement ?: $this->document;

        $elements = $contextElement->findAll('css', $cssSelector);
        \PHPUnit_FrameWork_Assert::assertNotEmpty($elements, "$cssSelector did not find any elements");
        return $elements;
    }

    protected function createFromDocument()
    {
        $this->document = $this->session->getPage();

        $path = parse_url($this->session->getCurrentUrl(), PHP_URL_PATH);
        \PHPUnit_FrameWork_Assert::assertArrayHasKey($path, $this->pageRegistry);

        $pageClass = $this->pageRegistry[$path];
        return new $pageClass($this->session);
    }
}

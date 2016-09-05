<?php

namespace Qafoo\Page;

use Qafoo\Page;

class Overview extends Page
{
    const PATH = '/';

    public function getVacationDays()
    {
        $page = $this->session->getPage();
        if (!($welcomeBox = $page->find('css', '.alert-info'))) {
            return null;
        }

        $text = $welcomeBox->getText();
        if (!preg_match('(You have (\d+) days of vacation left)', $text, $match)) {
            return null;
        }

        return (int) $match[1];
    }
}

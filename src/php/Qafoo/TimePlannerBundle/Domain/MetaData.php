<?php

namespace Qafoo\TimePlannerBundle\Domain;

use QafooLabs\MVC\TokenContext;
use Qafoo\UserBundle\Domain\User;

class MetaData extends DataObject
{
    /**
     * Author
     *
     * @var string
     */
    public $author;

    /**
     * Changed date
     *
     * @var \DateTime
     */
    public $changed;

    public function __construct($author, \DateTime $changed = null)
    {
        $this->author = $author;
        $this->changed = $changed ?: new \DateTime("now");
    }

    /**
     * Create from token context
     *
     * @param TokenContext $context
     * @return void
     */
    public static function fromContext(TokenContext $context)
    {
        return new static($context->getCurrentUser()->login);
    }
}

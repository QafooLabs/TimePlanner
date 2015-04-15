<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Kore\DataObject\DataObject;

use Qafoo\UserBundle\Domain\User;

class MetaData extends DataObject
{
    /**
     * Author
     *
     * @var User
     */
    public $author;

    /**
     * Changed date
     *
     * @var \DateTime
     */
    public $changed;

    public function __construct(User $author, \DateTimeInterface $changed = null)
    {
        $this->author = $author;
        $this->changed = $changed ?: new \DateTimeImmutable("now");
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Kore\DataObject\DataObject;

class Vacation extends DataObject
{
    /**
     * Vacation ID
     *
     * @var string
     */
    public $vacationId;

    /**
     * Start
     *
     * @var \DateTime
     */
    public $start;

    /**
     * End
     *
     * @var \DateTime
     */
    public $end;

    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * Comment
     *
     * @var string
     */
    public $comment;

    /**
     * Change meta data
     *
     * @var MetaData
     */
    public $metaData;
}

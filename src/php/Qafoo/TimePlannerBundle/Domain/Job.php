<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Kore\DataObject\DataObject;

class Job extends DataObject
{
    /**
     * Job ID
     *
     * @var string
     */
    public $jobId;

    /**
     * Job date
     *
     * @var \DateTime
     */
    public $month;

    /**
     * Customer
     *
     * @var string
     */
    public $customer;

    /**
     * Project
     *
     * @var string
     */
    public $project;

    /**
     * Person days
     *
     * @var Job\PersonDays
     */
    public $personDays;

    /**
     * Expected revenue
     *
     * May be a number or a formular based om minimumPersonDays
     *
     * @var strin
     */
    public $expectedRevenue;

    /**
     * Assignees
     *
     * @var Job\Assignment[]
     */
    public $assignees = array();

    /**
     * Invoice ID
     *
     * @var string
     */
    public $invoiceId;

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

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->personDays = $this->personDays ?: new Job\PersonDays(0, 0);
    }
}

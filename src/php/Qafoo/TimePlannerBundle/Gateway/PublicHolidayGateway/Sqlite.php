<?php

namespace Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

class Sqlite extends MySQL
{
    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        $query = $this->entityManager->getConnection()->query(
            "SELECT DISTINCT(strftime('%Y', p_date)) FROM public_holiday"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }
}

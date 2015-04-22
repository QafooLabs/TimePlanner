<?php

namespace Qafoo\TimePlannerBundle\Gateway\VacationGateway;

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
            "SELECT DISTINCT(strftime('%Y', v_start)) FROM vacation"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }
}

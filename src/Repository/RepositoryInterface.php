<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface RepositoryInterface
{

    /**
     * Entities Doctrine QueryBuilder
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllQueryB();
}

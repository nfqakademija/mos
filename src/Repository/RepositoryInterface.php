<?php
/**
 * Created by PhpStorm.
 * User: vaidas
 * Date: 18.11.28
 * Time: 14.49
 */

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

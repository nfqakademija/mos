<?php

namespace App\Services;

use App\Repository\RepositoryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Helper
{

    private $paginator;

    /**
     * Helper constructor.
     *
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Gets simple entities list paginated
     *
     * @param QueryBuilder $query
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getEntitiesPaginated(QueryBuilder $query, Request $request)
    {
        $page = $this->getPageFromRequest($request);

        $pagination = $this->paginator->paginate($query, $page, 15);

        return $pagination;
    }


    /**
     * Gets page number from GET.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getPageFromRequest(Request $request)
    {
        $page = 1;

        $pageGet = $request->query->get('page');
        if (!empty($pageGet)) {
            $page = (int)$pageGet;
        }

        return $page;
    }

    /**
     * Extracts dateFrom and dateTo from request, checks and returns as array
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws \Exception
     */
    public function dataFromRequest(Request $request)
    {
        try {
            $regionId = (int) $request->query->get('regionId');
            if ($regionId === null) {
                throw new \Exception('regionTitle is not specified.');
            }
        } catch (\Exception $e) {
            $regionId = 0;
        }
        
        try {
            $dateFromString = $request->query->get('dateFrom');
            if ($dateFromString === null) {
                throw new \Exception('dateFrom not specified.');
            }
            $dateFrom = new \DateTime($dateFromString);
        } catch (\Exception $e) {
            $dateFrom = new \DateTime('first day of this month');
        }

        try {
            $dateToString = $request->query->get('dateTo');
            if ($dateFromString === null) {
                throw new \Exception('dateTo not specified.');
            }
            $dateTo = new \DateTime($dateToString);
        } catch (\Exception $e) {
            $dateTo = new \DateTime('last day of this month');
        }
        
        
        return ['regionId' => $regionId, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo];
    }
}

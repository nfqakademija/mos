<?php

namespace App\Helper;

use App\Repository\RepositoryInterface;
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
     * @param \App\Repository\RepositoryInterface $repository
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool $wrapQueries
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getEntitiesPaginated(RepositoryInterface $repository, Request $request)
    {
        $page = $this->getPageFromRequest($request);

        $query = $repository->getAllQueryB();
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
            $page = (int) $pageGet;
        }

        return $page;
    }
}
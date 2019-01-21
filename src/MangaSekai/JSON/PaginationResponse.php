<?php declare(strict_types=1);
    namespace MangaSekai\JSON;
    
    trait PaginationResponse
    {
        /**
         * @param \Propel\Runtime\Util\PropelModelPager $pager    The paginator resulted from the propel query
         *
         * @return array The paginated response prepared to be returned by the server
         */
        function paginatedResponse (\Propel\Runtime\Util\PropelModelPager $pager): array
        {
            return array (
                'count' => $pager->getNbResults (),
                'page' => $pager->getPage (),
                'maxpages' => $pager->getLastPage (),
                'content' => $pager->getResults ()->toArray ()
            );
        }
    };
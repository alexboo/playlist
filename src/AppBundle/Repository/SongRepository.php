<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/17/16
 * Time: 1:33 PM
 */

namespace AppBundle\Repository;


use AppBundle\Request\SongFilter;
use Doctrine\ORM\EntityRepository;

class SongRepository extends EntityRepository {

    /**
     * Search all the songs is subject to the search query
     * @param SongFilter $request
     * @return array
     */
    public function findAllByFilters(SongFilter $request)
    {
        $response = [];

        $query = $this->createQueryBuilder("s");

        $parameters = $request->toFilter();

        foreach ($parameters as $name => $value) {
            $query->andWhere("s.$name = :$name");
        }
        $query->setParameters($parameters);

        /**
         *
         * If the request has been specified, the need to obtain data for the filters. Then we begin to collect values for filters
         * @Todo This place can be optimized, but so far it seems to me it is not important
         */
        if ($request->isFilters()) {
            $singers = [];
            $genres = [];
            $years = [];
            $total = 0;

            foreach ($query->getQuery()->getResult() as $song) {
                $singers[$song->getSinger()->getId()] = $song->getSinger();
                $genres[$song->getGenre()->getId()] = $song->getGenre();
                $years[$song->getYear()] = $song->getYear();
                $total ++;
            }

            $response['total'] = $total;
            $response['filters'] = ['singers' => array_values($singers), 'genres' => array_values($genres), 'years' => array_values($years)];
        }

        if ($request->getLimit()) {
            $query->setFirstResult($request->getOffset());
            $query->setMaxResults($request->getLimit());
        }

        $order = $request->getOrder();
        if ($order) {
            if ($order->field === 'singer') {
                $query->join('s.singer', 'sn');
                $query->orderBy('sn.name', $order->direction);
            } else if ($order->field === 'genre') {
                $query->join('s.genre', 'g');
                $query->orderBy('g.name', $order->direction);
            } else {
                $query->orderBy('s.' . $order->field, $order->direction);
            }
        }

        $response['rows'] = $query->getQuery()->getResult();

        return $response;
    }
}
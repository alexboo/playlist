<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/18/16
 * Time: 12:02 PM
 */

namespace AppBundle\Request;


use AppBundle\Entity\Song;

class SongFilter extends BaseRequest {

    protected $_excludes = ['limit', 'offset', 'filters', 'order', '_orderField'];
    protected $_prefix = 's';

    protected $_orderField = ['title', 'genre', 'singer', 'year'];

    protected $singer;
    protected $genre;
    protected $year;
    protected $limit = Song::MAX_NUM;
    protected $offset = 0;
    protected $filters = false;
    protected $order = 'title asc';

    /**
     * @return mixed
     */
    public function getSinger()
    {
        return $this->singer;
    }

    /**
     * @param mixed $singer
     */
    public function setSinger($singer)
    {
        $this->singer = $singer;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return boolean
     */
    public function isFilters()
    {
        return boolval($this->filters);
    }

    /**
     * @param boolean $filters
     */
    public function setFilters($filters)
    {
        $this->filters = boolval($filters);
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        if (!empty($this->order)) {
            $order = explode(' ', $this->order);
            if (in_array($order[0], $this->_orderField)) {
                /**
                 * @TODO create custom class with this properties
                 */
                $class = new \stdClass();
                $class->field = $order[0];
                $class->direction = (isset($order[1]) && $order[1] == 'desc') ? 'desc' : 'asc';
                return $class;
            }
        }

        return null;
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}
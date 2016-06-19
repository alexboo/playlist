<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/18/16
 * Time: 12:04 PM
 */

namespace AppBundle\Request;


use Symfony\Component\HttpFoundation\Request;

class BaseRequest {

    protected $_excludes = [];
    protected $_prefix = '';

    /**
     * Create request and fill all property with Request object
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        if ($request !== null) {
            $properties = get_object_vars($this);

            foreach($properties as $name => $value) {
                $value = $request->get($name);
                if ($value)
                    $this->{$name} = $request->get($name);
            }
        }
    }

    /**
     * Convert request properties to array for using in findBy function
     * @return array
     */
    public function toFilter()
    {
        $filter = [];
        $properties = get_object_vars($this);

        foreach ($properties as $name => $value) {
            if ($value != null) {
                if ($name == '_excludes' || $name == '_prefix' || in_array($name, $this->_excludes))
                    continue;

                $filter[$name] = $value;
            }
        }

        return $filter;
    }

    /**
     * To where sql, for all parameters used condition "AND"
     * @return string
     */
    public function toSqlWhere()
    {
        $where = [];
        $filter = $this->toFilter();
        foreach ($filter as $name => $value) {
            $where[] = $this->getPrefix() . $name . ' = :' . $name;
        }

        return implode(' AND ', $where);
    }

    protected function getPrefix()
    {
        return (!empty($this->_prefix) ? ($this->_prefix . '.') : '');
    }
}
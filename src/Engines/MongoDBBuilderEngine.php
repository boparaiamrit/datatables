<?php

namespace Boparaiamrit\Datatables\Engines;


use Boparaiamrit\Datatables\Request;
use Jenssegers\Mongodb\Query\Builder;

/**
 * Class MongoDBBuilderEngine
 *
 * @package Boparaiamrit\Datatables\Engines
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class MongoDBBuilderEngine extends QueryBuilderEngine
{
    /**
     * @param Builder $Builder
     * @param Request $request
     */
    public function __construct(Builder $Builder, Request $request)
    {
        parent::__construct($Builder, $request);
    }

    /**
     * Perform pagination
     *
     * @return void
     */
    public function paging()
    {
        $limit = (int)$this->request['length'];
        $start = (int)$this->request['start'];
        $this->query->skip($start)
            ->take($limit > 0 ? $limit : 10);
    }

    /**
     * Counts current query.
     *
     * @return int
     */
    public function count()
    {
        $myQuery = clone $this->query;

        return $myQuery->count();
    }

    /**
     * Compile query builder where clause depending on configurations.
     *
     * @param mixed  $query
     * @param string $column
     * @param string $keyword
     * @param string $relation
     */
    protected function compileQuerySearch($query, $column, $keyword, $relation = 'or')
    {
        $query->{$relation . 'WhereRaw'}([$column => ['$regex' => '^' . trim($keyword, '%'), '$options' => '$i']]);
    }

    /**
     * Wrap a column and cast in pgsql.
     *
     * @param  string $column
     *
     * @return string
     */
    public function castColumn($column)
    {
        return $column;
    }
}

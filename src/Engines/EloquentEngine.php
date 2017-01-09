<?php

namespace Boparaiamrit\Datatables\Engines;


use Boparaiamrit\Datatables\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentEngine.
 *
 * @package Boparaiamrit\Datatables\Engines
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class EloquentEngine extends QueryBuilderEngine
{
    /**
     * @param mixed                            $model
     * @param \Boparaiamrit\Datatables\Request $request
     */
    public function __construct($model, Request $request)
    {
        $builder = $model instanceof Builder ? $model : $model->getQuery();
        parent::__construct($builder->getQuery(), $request);

        $this->query = $builder;
        $this->query_type = 'eloquent';
    }
}

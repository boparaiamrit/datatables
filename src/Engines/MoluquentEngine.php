<?php

namespace Boparaiamrit\Datatables\Engines;


use Boparaiamrit\Datatables\Request;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * Class EloquentEngine.
 *
 * @package Boparaiamrit\Datatables\Engines
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class MoluquentEngine extends MongoDBBuilderEngine
{
	/**
	 * @param mixed                            $Model
	 * @param \Boparaiamrit\Datatables\Request $request
	 */
	public function __construct($Model, Request $request)
	{
		/** @var Builder $Builder */
		$Builder = $Model instanceof Builder ? $Model : $Model->getQuery();
		
		/** @var \Jenssegers\Mongodb\Query\Builder $QueryBuilder */
		$QueryBuilder = $Builder->getQuery();
		parent::__construct($QueryBuilder, $request);
		
		$this->query      = $Builder;
		$this->query_type = 'moluquent';
	}
}

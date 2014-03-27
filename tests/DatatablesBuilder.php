<?php

use Mockery as m;
use yajra\Datatables\Datatables;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class DatatablesBuilderTest extends PHPUnit_Framework_TestCase  {

	public function setUp()
	{
		parent::setUp();
		$app = m::mock('AppMock');
		$app->shouldReceive('instance')->once()->andReturn($app);

		Illuminate\Support\Facades\Facade::setFacadeApplication($app);
		Config::swap($config = m::mock('ConfigMock'));
	}

	public function tearDown()
	{
		m::close();
	}

	public function test_datatables_make()
	{
		$cache = m::mock('stdClass');
		$driver = m::mock('stdClass');

		$builder = $this->getBuilder();
		$builder->select('id','name')->from('users');

		$builder->getConnection()->shouldReceive('raw');
		$builder->getConnection()->shouldReceive('table')->andReturn($builder);
		$builder->getConnection()->shouldReceive('getName')->andReturn('foo');
		// $builder->getConnection()->shouldReceive('setBindings')->andReturn($builder);

		$builder->getConnection()->shouldReceive('getCacheManager')->times(3)->andReturn($cache);
		$cache->shouldReceive('driver')->times(3)->andReturn($driver);
		$driver->shouldReceive('remember')->times(3)->andReturn(array());

		// $builder->getConnection()->shouldReceive('count')->andReturn(2);

		Config::shouldReceive('get');

		$response = Datatables::of($builder)->make();

		$this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
	}

	public function test_datatables_make_with_data()
	{
		$cache = m::mock('stdClass');
		$driver = m::mock('stdClass');
		$data = array(
			array('id' => 1, 'name' => 'foo'),
			array('id' => 2, 'name' => 'bar'),
			);
		$builder = m::mock('Illuminate\Database\Query\Builder');
		$builder->shouldReceive('select')->once()->with(array('id','name'))->andReturn($builder);
		$builder->shouldReceive('from')->once()->with('users')->andReturn($builder);
		$builder->shouldReceive('get')->once()->andReturn($data);

		$builder->columns = array('id', 'name');
		$builder->select(array('id', 'name'))->from('users');

		// ******************************
		// Datatables::of() mocks
		// ******************************
		$builder->shouldReceive('getConnection')->andReturn(m::mock('Illuminate\Database\Connection'));

		// ******************************
		// Datatables::make() mocks
		// ******************************
		$builder->shouldReceive('toSql')->times(4)->andReturn('select id, name from users');
		$builder->getConnection()->shouldReceive('raw')->once()->andReturn('select \'1\' as row_count');
		$builder->shouldReceive('select')->once()->andReturn($builder);
		$builder->getConnection()->shouldReceive('raw')->andReturn('(select id, name from users) count_row_table');
		$builder->shouldReceive('select')->once()->andReturn($builder);
		$builder->getConnection()->shouldReceive('table')->times(2)->andReturn($builder);
		$builder->shouldReceive('getBindings')->times(2)->andReturn(array());
		$builder->shouldReceive('setBindings')->times(2)->with(array())->andReturn($builder);
		$builder->shouldReceive('remember')->times(2)->with(true)->andReturn($builder);
		$builder->shouldReceive('count')->times(2)->andReturn(2);

		Config::shouldReceive('get')->times(5);

		// set Input variables
		$_GET['sEcho'] = 1;

		$response = Datatables::of($builder)->make();
		$actual = $response->getContent();
		$expected = '{"sEcho":1,"iTotalRecords":2,"iTotalDisplayRecords":2,"aaData":[[1,"foo"],[2,"bar"]],"sColumns":["id","name"]}';

		$this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
		$this->assertEquals($expected, $actual);
	}

	public function test_datatables_make_with_data_and_uses_mdata()
	{
		$cache = m::mock('stdClass');
		$driver = m::mock('stdClass');
		$data = array(
			array('id' => 1, 'name' => 'foo'),
			array('id' => 2, 'name' => 'bar'),
			);
		$builder = m::mock('Illuminate\Database\Query\Builder');
		$builder->shouldReceive('select')->once()->with(array('id','name'))->andReturn($builder);
		$builder->shouldReceive('from')->once()->with('users')->andReturn($builder);
		$builder->shouldReceive('get')->once()->andReturn($data);

		$builder->columns = array('id', 'name');
		$builder->select(array('id', 'name'))->from('users');

		// ******************************
		// Datatables::of() mocks
		// ******************************
		$builder->shouldReceive('getConnection')->andReturn(m::mock('Illuminate\Database\Connection'));

		// ******************************
		// Datatables::make() mocks
		// ******************************
		$builder->shouldReceive('toSql')->times(4)->andReturn('select id, name from users');
		$builder->getConnection()->shouldReceive('raw')->once()->andReturn('select \'1\' as row_count');
		$builder->shouldReceive('select')->once()->andReturn($builder);
		$builder->getConnection()->shouldReceive('raw')->andReturn('(select id, name from users) count_row_table');
		$builder->shouldReceive('select')->once()->andReturn($builder);
		$builder->getConnection()->shouldReceive('table')->times(2)->andReturn($builder);
		$builder->shouldReceive('getBindings')->times(2)->andReturn(array());
		$builder->shouldReceive('setBindings')->times(2)->with(array())->andReturn($builder);
		$builder->shouldReceive('remember')->times(2)->with(true)->andReturn($builder);
		$builder->shouldReceive('count')->times(2)->andReturn(2);

		Config::shouldReceive('get')->times(5);

		// set Input variables
		$_GET['sEcho'] = 1;

		$response = Datatables::of($builder)->make(true);
		$actual = $response->getContent();
		$expected = '{"sEcho":1,"iTotalRecords":2,"iTotalDisplayRecords":2,"aaData":[{"id":1,"name":"foo"},{"id":2,"name":"bar"}],"sColumns":["id","name"]}';

		$this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
		$this->assertEquals($expected, $actual);
	}

	protected function getBuilder()
	{
		$grammar = new Illuminate\Database\Query\Grammars\Grammar;
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		return new Builder(m::mock('Illuminate\Database\Connection'), $grammar, $processor);
	}

}
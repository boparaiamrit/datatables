<?php


use Boparaiamrit\Datatables\Datatables;
use Boparaiamrit\Datatables\Request;

class DatatablesTest extends PHPUnit_Framework_TestCase
{
    public function test_get_html_builder()
    {
        $datatables = $this->getDatatables();
        $html = $datatables->getHtmlBuilder();

        $this->assertInstanceOf('Boparaiamrit\Datatables\Html\Builder', $html);
    }

    public function test_get_request()
    {
        $datatables = $this->getDatatables();
        $request = $datatables->getRequest();

        $this->assertInstanceOf('Boparaiamrit\Datatables\Request', $request);
    }

    /**
     * @return \Boparaiamrit\Datatables\Datatables
     */
    protected function getDatatables()
    {
        $datatables = new Datatables(Request::capture());

        return $datatables;
    }
}

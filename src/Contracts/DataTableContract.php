<?php

namespace Boparaiamrit\Datatables\Contracts;

/**
 * Interface DataTableContract
 *
 * @package Boparaiamrit\Datatables\Contracts
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
interface DataTableContract
{
    /**
     * Render view.
     *
     * @param       $view
     * @param array $data
     * @param array $mergeData
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = []);

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax();

    /**
     * @return \Boparaiamrit\Datatables\Html\Builder
     */
    public function html();

    /**
     * @return \Boparaiamrit\Datatables\Html\Builder
     */
    public function builder();

    /**
     * @return \Boparaiamrit\Datatables\Request
     */
    public function request();

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query();
}

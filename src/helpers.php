<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RyanWinchester\Paginates\Paginator;

if (! function_exists('paginate')) {
    /**
     * Paginate a model.
     *
     * @param  Model|Builder|string $model
     * @param  array  $params
     * @param  array  $defaults
     * @return LengthAwarePaginator
     */
    function paginate($model, $params = null, $defaults = [])
    {
        $paginator = new Paginator($defaults);

        return $paginator->paginate($model, $params);
    }
}

<?php

namespace RyanWinchester\Paginates;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait PaginatesModels
{
    /**
     * The default amount of items per page.
     *
     * @var int
     */
    protected $defaultPerPage = 100;

    /**
     * The default column to order by.
     *
     * @var string
     */
    protected $defaultOrderCol = 'created_at';

    /**
     * The default direction to order by.
     *
     * @var string
     */
    protected $defaultOrderDir = 'desc';

    /**
     * Get a paginator using filters from the request.
     *
     * @param  Model|Builder|string  $model
     * @param  array  $params
     * @param  array  $defaults
     * @return LengthAwarePaginator
     */
    protected function paginate($model, $params = null, $defaults = [])
    {
        $defaults = [
            'perPage' => $defaults['perPage'] ?? $this->defaultPerPage,
            'orderCol' => $defaults['orderCol'] ?? $this->defaultOrderCol,
            'orderDir' => $defaults['orderDir'] ?? $this->defaultOrderDir,
        ];

        $paginator = new Paginator($defaults);

        return $paginator->paginate($model, $params);
    }
}

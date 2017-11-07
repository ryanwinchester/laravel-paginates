<?php

namespace RyanWinchester\Paginates;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Paginator
{
    /**
     * The default amount of items per page.
     *
     * @var int
     */
    protected $defaultPerPage;

    /**
     * The default column to order by.
     *
     * @var string
     */
    protected $defaultOrderCol;

    /**
     * The default direction to order by.
     *
     * @var string
     */
    protected $defaultOrderDir;

    /**
     * Paginator constructor.
     *
     * @param  array  $defaultParams
     */
    public function __construct($defaultParams = [])
    {
        $this->defaultPerPage = $defaultParams['perPage'] ?? 100;
        $this->defaultOrderCol = $defaultParams['orderCol'] ?? 'created_at';
        $this->defaultOrderDir = $defaultParams['orderDir'] ?? 'desc';
    }

    /**
     * Get a paginator using filters from the request.
     *
     * @param  Model|Builder|string  $model
     * @param  array  $params
     * @return LengthAwarePaginator
     */
    public function paginate($model, $params = [])
    {
        if (is_null($params)) {
            $params = app('request')->all();
        }

        $params = $this->parseParams($params);

        return $this->getBuilderInstance($model, $params['include'])
            ->where($params['filter'])
            ->orderBy($params['orderBy']['col'], $params['orderBy']['dir'])
            ->paginate($params['perPage'], $params['columns'])
            ->appends($this->getAppends($params));
    }

    /**
     * Parse the incoming params.
     *
     * @param  array $params
     * @return array
     */
    private function parseParams($params)
    {
        return [
            'filter' => $this->getFilter($params),
            'perPage' => $this->getPerPage($params),
            'columns' => $this->getColumns($params),
            'include' => $this->getIncludes($params),
            'orderBy' => $this->getOrderBy($params),
        ];
    }

    /**
     * Get the items instance.
     *
     * @param  Model|string  $model
     * @param  array $includes
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    private function getBuilderInstance($model, $includes)
    {
        if (! empty($includes)) {
            return $model::with(...$includes);
        }

        return is_string($model) ? new $model : $model;
    }

    /**
     * Get the filter closure from the opts array.
     *
     * @param  array $params
     * @return \Closure
     */
    private function getFilter($params)
    {
        return $params['filter'] ?? function() {
                //
            };
    }

    /**
     * Get the amount of items per page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    private function getPerPage($params)
    {
        return (int) ($params['perPage'] ?? $this->defaultPerPage);
    }

    /**
     * Limit the columns per model.
     *
     * @param  array $params
     * @return array
     */
    private function getColumns($params)
    {
        return explode(',', $params['columns'] ?? '*');
    }

    /**
     * Include specific model relationships.
     *
     * @param  array $params
     * @return array|null
     */
    private function getIncludes($params)
    {
        if (empty($params['include'])) {
            return null;
        }

        return explode(',', $params['include']);
    }

    /**
     * Order the items by a certain column and direction.
     *
     * @param  array $params
     * @return array
     */
    private function getOrderBy($params)
    {
        $orderStr = $params['orderBy'] ?? $this->defaultOrderCol.'|'.$this->defaultOrderDir;

        $orderBy = explode('|', $orderStr);

        if (count($orderBy) === 1) {
            array_push($orderBy, $this->defaultOrderDir);
        }

        return [
            'col' => $orderBy[0],
            'dir' => $orderBy[1],
        ];
    }

    /**
     * Get the parameters to append to the pagination object's query string.
     *
     * @param  array $params
     * @return array
     */
    private function getAppends($params)
    {
        return array_only($params, [
            'perPage',
            'columns',
            'include',
            'orderBy',
        ]);
    }
}

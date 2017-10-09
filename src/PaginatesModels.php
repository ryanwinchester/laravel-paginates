<?php

namespace RyanWinchester\Paginates;

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
    protected $defaultOrderDir = 'asc';

    /**
     * Get a paginator using filters from the request.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginate($model, $params = null)
    {
        if (is_null($params)) {
            $params = app('request')->all();
        }

        $params = $this->parseParams($params);

        return $this->itemsInstance($model, $params['includes'])
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
            'includes' => $this->getIncludes($params),
            'orderBy' => $this->getOrderBy($params),
        ];
    }

    /**
     * Get the items instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  array $includes
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function itemsInstance($model, $includes)
    {
        if (! empty($includes)) {
            return $model::with(...$includes);
        }

        if (is_string($model)) {
            return new $model;
        }

        return $model;
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

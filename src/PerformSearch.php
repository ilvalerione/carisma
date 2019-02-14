<?php

namespace Carisma;


trait PerformSearch
{
    protected static $operators = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
    ];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    protected static $search;

    /**
     * Apply search logic to the model
     *
     * @param \Carisma\Http\Requests\CarismaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applySearch($request, $query)
    {
        foreach ($request->getFilters() as $field => $constraints) {
            foreach ($constraints as $operator => $value) {
                $query->where($field, static::$operators[$operator], $value);
            }
        }

        return empty($request->query('search'))
            ? $query
            : static::filterBySearchParam($query, $request->query('search'));
    }

    /**
     * Apply search logic to the model
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filterBySearchParam($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            // If the query string is numeric we filter searching exact correspondence
            // If the query string is a primitive string type we use LIKE operator
            if (is_numeric($search) && in_array($query->getModel()->getKeyType(), ['int', 'integer'])) {
                $query->orWhere($query->getModel()->getQualifiedKeyName(), $search);
            }

            $model = $query->getModel();

            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';

            foreach (static::searchableColumns() as $column) {
                $query->orWhere($model->qualifyColumn($column), $likeOperator, '%' . $search . '%');
            }
        });
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return empty(static::$search)
            ? [static::newModel()->getKeyName()]
            : static::$search;
    }
}
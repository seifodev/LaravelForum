<?php


namespace App\Filters;
use Illuminate\Http\Request;

Abstract class Filters
{
    protected $request;
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;
    protected $filters = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder
     */
    public function apply($builder)
    {

        $this->builder = $builder;

        // TODO:: check this out
        /*
         * $this->getFilters()
            ->filter(function ($filter) {

                return method_exists($this, $filter);
            })
            ->each(function ($filter, $value) {

                return $this->$filter($value);
            });
         */

        foreach ($this->getFilters() as $filter => $value)
        {
            if(method_exists($this, $filter))
            {
                return $this->$filter($value);
            }
        }

        return $this->builder;

    }


    public function getFilters()
    {
        return $this->request->only($this->filters);
    }

}
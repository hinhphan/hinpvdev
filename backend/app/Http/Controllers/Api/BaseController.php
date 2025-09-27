<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\CheckAuthorize;
use App\Traits\JsonRespondController;

class BaseController extends Controller
{
    use JsonRespondController, CheckAuthorize;

    /**
     * Default sort column
     * @var string
     */
    protected $sortColumn = 'created_at';

    /**
     * Default sort direction
     * @var string
     */
    protected $sortDirection = 'asc';

    /**
     * Valid sort values
     * @var array
     */
    protected $validSortValues = [
        'created_at',
        'updated_at',
        '-created_at',
        '-updated_at',
    ];

    /**
     * Get pagination size from request or use default value
     */
    public function getPaginationSize()
    {
        return request()->input('size', config('constants.pagination_default_size', 10));
    }

    /**
     * Set sort column and direction from request
     * @return void
     */
    protected function setSort()
    {
        if (request()->has('sort')) {
            $sort = request()->input('sort', null);

            if (is_null($sort) || empty($sort) || !in_array($sort, $this->validSortValues)) {
                return;
            }

            $this->sortDirection = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $this->sortColumn = ltrim($sort, '-');
        }            
    }

    public function getSortColumn()
    {
        $this->setSort();
        return $this->sortColumn;
    }

    /**
     * Get sort direction
     * @return string
     */
    public function getSortDirection()
    {
        $this->setSort();
        return $this->sortDirection;
    }

    /**
     * Set valid sort values
     * @param array $values
     * @return void
     */
    public function setValidSortValues(array $values)
    {
        $this->validSortValues = $values;
    }
}

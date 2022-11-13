<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

trait ApiResponser
{
    /**
     * @var int The default pagination size.
     */
    protected $pagination = 5;

    /**
     * @var int The minimum pagination size.
     */
    protected $minLimit = 1;

    /**
     * Getter for the pagination.
     *
     * @return int The pagination size.
     */
    public function getPagination(): int
    {
        return $this->pagination;
    }

    /**
     * Sets and checks the pagination.
     *
     * @param int $pagination The given pagination.
     */
    public function setPagination($pagination)
    {
        $this->pagination = (int) $this->checkPagination($pagination);
    }

    /**
     * Checks the pagination.
     *
     * @param * $pagination The pagination.
     *
     * @return int The corrected pagination.
     */
    private function checkPagination($pagination): int
    {
        // Pagination should be numeric
        if (!is_numeric($pagination)) {
            return $this->pagination;
        }
        // Pagination should not be less than the minimum limitation
        if ($pagination < $this->minLimit) {
            return $this->minLimit;
        }
        // If the pagination is between the min limit and the max limit, return the pagination
        if (!($pagination < $this->minLimit)) {
            return $pagination;
        }

        // If all fails, return the default pagination
        return $this->pagination;
    }

    /**
     * Paginate a given collection.
     *
     * @param Collection $collection The collection.
     * @param int $page The page number.
     * @return void
     */
    public function paginateCollection(Collection $collection, $page)
    {
        return array_values(
            $collection->slice(($page - 1) * $this->getPagination())
                ->take($this->getPagination())->all()
        );
    }

    /**
     * Method used to paginate a set of items.
     *
     * @param LengthAwarePaginator $paginator The paginator.
     * @param string[] $data The data to be paginated
     * @param string[] $counters The quantity of the subMenu items
     * @param string[] $headers The headers to be send.
     *
     * @return JsonResponse The paginated results in a JSON response.
     */
    public function respondWithPagination(
        LengthAwarePaginator $paginator,
        array $data,
        array $counters = [],
        array $headers = []
    ): JsonResponse {
        $data = [
            'data' => $data,
            'pagination' => [
                'counters' => $counters,
                'last_page' => $paginator->lastPage(),
                'current_page' => $paginator->currentPage(),
                'limit' => $paginator->perPage(),
                'total_count' => $paginator->total(),
            ],
        ];

        return $this->successResponse($data);
    }
    
    /**
     * Build successfull response.
     * @param string|array $data
     * @param int $code
     * @param Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Build error response.
     * @param string|array $message
     * @param int $code
     * @param Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}

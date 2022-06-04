<?php

namespace App\Util;

use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class PaginatorUtil
{
    public static function getPaginatorInfo($query, $elementsPerPage, $actualPage, $groupByDate = false)
    {
        $paginator = new \stdClass();
        $paginator->totalItems = $query->count();
        $paginator->data = $query->skip(($actualPage - 1) * $elementsPerPage)
            ->limit($elementsPerPage)
            ->get();
        if ($groupByDate) {
            $items = $paginator->data->groupBy(
                function ($date) {
                    return Carbon::parse($date->updated_at)->format('Y-m-d');
                }
            );
            $data = array();
            foreach ($items as $key => $value) {
                $item = new \stdClass();
                $item->date = $key;
                $item->list = $value;
                $data[] = $item;
            }
            $paginator->data = $data;
        }
        $pag = new LengthAwarePaginator($paginator->data, $paginator->totalItems, $elementsPerPage, $actualPage);
        $paginator->totalPages = $pag->lastPage();
        $paginator->hasNext = $pag->hasMorePages();
        $paginator->hasPrevious = $actualPage > 1;
        return $paginator;
    }
}

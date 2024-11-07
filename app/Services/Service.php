<?php

namespace App\Services;
use Carbon\Carbon;

abstract class Service
{
    protected function applyTimeFrameFilter(&$query, $timeFrame)
    {
        if ($timeFrame) {
            switch ($timeFrame) {
                case 'today':
                case 'day':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'weekend':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek()->addDays(5), // Samedi
                        Carbon::now()->startOfWeek()->addDays(6)  // Dimanche
                    ]);
                    break;
            }
        }
    }
    
    

    protected function getPagination($data)
    {
        return [
            'pagination' => [
                'totalCount' => $data->total(),
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'perPage' => $data->perPage(),
            ]
        ];
    }


}

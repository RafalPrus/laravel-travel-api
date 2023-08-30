<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request)
    {
//        $tours = $travel->tours()
//            ->when($request->priceFrom, function($query) use($request) {
//                $query->where('price', '>=', $request->priceFrom * 100);
//            })
//            ->when($request->priceTo, function($query) use($request) {
//                $query->where('price', '<=', $request->priceTo * 100);
//            })
//            ->when($request->dateFrom, function($query) use($request) {
//                $query->where('starting_date', '>=', $request->dateFrom);
//            })
//            ->when($request->dateTo, function($query) use($request) {
//                $query->where('starting_date', '<=', $request->dateTo);
//            })
//            ->when($request->sortBy && $request->sortOrder, function($query) use ($request) {
//                $query->orderBy($request->sortBy, $request->sortOrder);
//            })
//            ->orderBy('starting_date')
//            ->paginate();

        $tours = QueryBuilder::for($travel->tours())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::scope('price_from'),
                AllowedFilter::scope('price_to'),
                AllowedFilter::scope('date_from'),
                AllowedFilter::scope('date_to'),
                ])
            ->allowedSorts('price')
            ->orderBy('starting_date')
            ->paginate();

        return TourResource::collection($tours);
    }
}

<?php


namespace App\Repositories;


use App\Product;
use Illuminate\Support\Facades\DB;

class ProductSearchRepository {

    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function search($query) {

//        return $this->product->where(strtolower('title'), 'like', '%' . $query . '%')
//            ->orderBy('year')
//            ->get();

        return $this->product->whereRaw('LOWER(title) like (?)', ["%{$query}%"]) // done this way because production db cant recognize uppercase values
            ->orderBy('year')
            ->get();
    }

    public function searchByActor($query)
    {
        return $this->product->whereHas('actors' , function ($q) use ($query) {
            $q->whereRaw('LOWER(name) = ?', [$query]);
            })
            ->orderBy('year')
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use DebugBar\StandardDebugBar;
use DebugBar\DataCollector\TimeDataCollector;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index() {

        $products = $this->productService->allPaginated(16);

        return view('products.trending')
            ->with('products', $products);
    }

    public function showSingle($slug) {

        $product = $this->productService->findBySlug($slug);

        return view('products.single')->with('product', $product);
    }

    public function search(Request $request) {

        $debugbar = new StandardDebugBar();

        $debugbar['time']->startMeasure('longop', 'My long operation');
//        sleep(2);
        $debugbar['time']->stopMeasure('longop');

        $debugbar['time']->measure('My long operation', function() {
//            sleep(2);
        });

        $query = $request->input('search');

        $movies = $this->productService->processSearch($query);

        if (!empty($movies)) {

            return view('products.results')
                ->with('movies', $movies);
        }
        else if (empty($query)) {

            return Redirect::to('/')
                ->withErrors(['Type something into search', 'The Message']);
        }

        return Redirect::to('/')
            ->withErrors(['No results', 'The Message']);
    }
}
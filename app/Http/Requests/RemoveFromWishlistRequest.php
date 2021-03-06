<?php

namespace App\Http\Requests;

use App\Services\ProductService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Rules\IsInWishlist;

class RemoveFromWishlistRequest extends FormRequest
{

    protected $productService;

    public function __construct(Request $request1, ProductService $productService, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->productService = $productService;

        $this->product = $this->productService->findBySlug(request()->slug);

        $request1->merge(['product_id' => $this->product->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()) {

            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => new IsInWishlist($this->productService)
        ];
    }

    public function messages()
    {
        return[
//            'product_id.required' => 'Cannot remove item, it is not in your wishlist'
        ];
    }
}

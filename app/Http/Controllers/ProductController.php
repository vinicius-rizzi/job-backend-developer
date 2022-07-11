<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * Construct.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    /**
     * List products.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $products = $this->productRepository->index(new ParameterBag($request->all()));

        return ProductResource::collection($products);
    }

    /**
     * Create a product.
     *
     * @param StoreProductRequest $request
     * @return ProductResource
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = $this->productRepository->save(new ParameterBag($request->validated()));

        return new ProductResource($product);
    }

    /**
     * Find a product.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update a product.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->productRepository->update(new ParameterBag($request->validated()), $product);

        return new ProductResource($product);
    }

    /**
     * Delete a product.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product): Response
    {
        $this->productRepository->delete($product);

        return response(['status' => 'success']);
    }
}

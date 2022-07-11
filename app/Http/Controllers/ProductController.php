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
     * Construtor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    /**
     * Lista produtos.
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
     * Cria um produto.
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
     * Busca um produto.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Altera um produto.
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
     * Exclui um produto.
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

<?php

namespace App\Repositories;

use App\Models\Product;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProductRepository
{
    /**
     * Lista produtos.
     *
     * @param ParameterBag $params
     * @return void
     */
    public function index(ParameterBag $params)
    {
        $products = Product::query();

        if ($params->get('search')) {
            $products->where('name', 'like', '%' . $params->get('search') . '%')
                ->orWhere('category', 'like', '%' . $params->get('search') . '%');
        }

        if ($params->get('category')) {
            if (!is_null($params->get('search'))) {
                $products->orWhere('category', $params->get('category'));
            } else {
                $products->where('category', $params->get('category'));
            }
        }

        if (!is_null($params->get('image'))) {
            $productsIds = $products->pluck('id')->all();

            $products = Product::whereIn('id', $productsIds);

            if ($params->get('image')) {
                $products->whereNotNull('image_url');
            } else {
                $products->whereNull('image_url');
            }
        }

        return $products->paginate($params->get('per_page', 25));
    }

    /**
     * Cria um produto.
     *
     * @param ParameterBag $params
     * @return Product
     */
    public function save(ParameterBag $params): Product
    {
        $product = $this->mapPayload($params, (new Product()));

        $product->save();

        return $product;
    }

    /**
     * Altera um produto.
     *
     * @param ParameterBag $params
     * @param Product $product
     * @return Product
     */
    public function update(ParameterBag $params, Product $product): Product
    {
        $product = $this->mapPayload($params, $product);

        $product->update();

        return $product;
    }

    /**
     * Mapeia o payload conforme os itens do Product.
     *
     * @param ParameterBag $params
     * @param Product $product
     * @return Product
     */
    private function mapPayload(ParameterBag $params, Product $product): Product
    {
        $product->name = $params->get('name') ?? $product->name;
        $product->price = $params->get('price') ?? $product->price;
        $product->description = $params->get('description') ?? $product->description;
        $product->category = $params->get('category') ?? $product->category;
        $product->image_url = $params->get('image') ?? $product->image_url;

        return $product;
    }

    /**
     * Exclui uma conta.
     *
     * @param Product $product
     * @return void
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }
}

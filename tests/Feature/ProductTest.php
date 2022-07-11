<?php

namespace Tests\Feature;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testItCreatesAProduct()
    {
        $params = Product::factory()->make();

        $product = (new ProductRepository())->save(new ParameterBag($params->toArray()));

        $this->assertEquals($product->name, $params->toArray()['name']);
    }

    public function testItListsAProduct()
    {
        $productsParams = Product::factory()->count(5)->make();

        foreach ($productsParams as $params) {
            (new ProductRepository())->save(new ParameterBag($params->toArray()));
        }

        $products = (new ProductRepository())->index(new ParameterBag());

        $response = collect($products)->toArray();

        $this->assertNotEmpty($response['data']);
    }

    public function testItShowAProduct()
    {
        $params = Product::factory()->make();

        $product = (new ProductRepository())->save(new ParameterBag($params->toArray()));

        $show = collect((new ProductController(new ProductRepository()))->show($product))->toArray();

        $this->assertEquals($product['name'], $show['name']);
    }

    public function testItUpdateAProduct()
    {
        $params = Product::factory()->make();

        $product = (new ProductRepository())->save(new ParameterBag($params->toArray()));

        $newParams = Product::factory()->make();

        $productUpdated = (new ProductRepository())->update(new ParameterBag($newParams->toArray()), $product);

        $this->assertEquals($newParams->toArray()['name'], $productUpdated->name);
    }

    public function testItDeleteAProduct()
    {
        $params = Product::factory()->make();

        $product = (new ProductRepository())->save(new ParameterBag($params->toArray()));

        (new ProductRepository())->delete($product);

        $findProduct = Product::find($product->id);

        $this->assertEmpty($findProduct);
    }
}

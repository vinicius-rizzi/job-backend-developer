<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\ParameterBag;
use Throwable;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('id') > 20) {
            return (new ConsoleOutput())->writeln('Id inválido, informe um id de 1 a 20.');
        }

        $url = $this->getUrl($this->option('id'));

        $products = $this->getProducts($url);

        foreach ($products as $product) {
            if ($this->checkIfExists($product['title'])) {
                (new ConsoleOutput())->writeln(
                    'Item '.$product['title'].' não importado, já existe um registro cadastrado na base com esse nome.'
                );
            } else {
                $params = $this->prepareForSave($product);

                (new ProductRepository())->save(new ParameterBag($params));
            }
        }

        return (new ConsoleOutput())->writeln('Importação finalizada.');
    }

    /**
     * Create a url according to command.
     *
     * @param integer|null $id
     * @return string
     */
    private function getUrl(int $id = null): string
    {
        $url = Config::get('fakestoreapi.url');

        if (is_null($id)) {
            return $url;
        }

        return $url.'/'.$this->option('id');
    }

    /**
     * Search for products in the fakestore api.
     *
     * @param string $url
     * @return array
     */
    private function getProducts(string $url): array
    {
        try {
            $products = Http::get($url)->json();

            if (array_key_exists(0, $products)) {
                return $products;
            }

            return [$products];
        } catch (Throwable $th) {
            Log::error($th);
        }
    }

    /**
     * Checks if there is already an item registered with the same name.
     *
     * @param string $name
     * @return boolean
     */
    private function checkIfExists(string $name): bool
    {
        $product = Product::where('name', $name)->first();

        if (is_null($product)) {
            return false;
        }

        return true;
    }

    /**
     * Adjust items for save.
     *
     * @param array $product
     * @return array
     */
    public function prepareForSave(array $product): array
    {
        return [
            'name' => $product['title'],
            'price' => $product['price'],
            'description' => $product['description'],
            'category' => $product['category'],
            'image' => $product['image']
        ];
    }
}

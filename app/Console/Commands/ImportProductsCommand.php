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
        $url = Config::get('fakestoreapi.url');

        if (!$this->option('id')) {
            $products = $this->getProducts($url);
        } else {
            if ($this->option('id') <= 20) {
                $url = $url . '/' . $this->option('id');

                $products = [$this->getProducts($url)];
            } else {
                return (new ConsoleOutput())->writeln('Id inválido, informe um id de 1 a 20.');
            }
        }

        if (!is_null($products)) {
            foreach ($products as $product) {
                if ($this->verifyExists($product['title'])) {
                    (new ConsoleOutput())->writeln('Item '.$product['title'].' não importado, já existe um item cadastrado na base com esse nome.');
                } else {
                    $params = [
                        'name' => $product['title'],
                        'price' => $product['price'],
                        'description' => $product['description'],
                        'category' => $product['category'],
                        'image' => $product['image']
                    ];

                    (new ProductRepository())->save(new ParameterBag($params));
                }
            }
        }

        return (new ConsoleOutput())->writeln('Importação finalizada.');
    }

    /**
     * Busca produtos na api fakestore.
     *
     * @param string $url
     * @return array
     */
    private function getProducts(string $url): array
    {
        try {
            return Http::get($url)->json();
        } catch (Throwable $th) {
            Log::error($th);
        }
    }

    /**
     * Verifica se já existe um item cadastrado com o mesmo nome.
     *
     * @param string $name
     * @return boolean
     */
    private function verifyExists(string $name): bool
    {
        $product = Product::where('name', $name)->first();

        if (!is_null($product)) {
            return true;
        }

        return false;
    }
}

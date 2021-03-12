<?php 

namespace App\Utility;

use App\Models\Product;
use App\Models\Exchange;

class ExchangeImporter 
{
    private $zenbot_location = '';
    
    function __construct(string $zenbot_location) 
    {
        // Check location exists etc
        $this->zenbot_location = $zenbot_location;
    }
    
    public function run() 
    {
        $dir_contents = scandir($this->zenbot_location.'/extensions/exchanges');

        $exchange_names = array_filter($dir_contents, function($name) {
            return ! in_array($name, ['.', '..', '_stub', 'sim']);
        });

        foreach ($exchange_names as $exchange_name) {
            $exchange = Exchange::create(['name' => $exchange_name]);

            $products_file = file_get_contents("{$this->zenbot_location}/extensions/exchanges/$exchange_name/products.json");

            foreach (json_decode($products_file) as $product) {
                $data = (array) $product;
                $data['name'] = $data['label'];
                $data['exchange_id'] = $exchange->id;

                Product::create($data);
            }
        }
    }
}
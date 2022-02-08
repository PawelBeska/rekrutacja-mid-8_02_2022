<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    /**
     * @param \App\Models\Product $product
     */
    public function __construct(private Product $product = new Product())
    {
    }

    /**
     * @return \App\Models\Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function assignData(array $data): static
    {
        $this->product->name = $data['name'];
        $this->product->description = $data['description'];
        $this->product->save();
        return $this;
    }

}

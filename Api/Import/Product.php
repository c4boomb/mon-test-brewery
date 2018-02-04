<?php

namespace Lev\Brewery\Api\Import;

use Magento\Catalog\Api\Data\ProductInterface;

interface Product {
    public function importProduct(ProductInterface $product) : Product;
}
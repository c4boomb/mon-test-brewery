<?php

namespace Lev\Brewery\Api\Import;

use Lev\Brewery\Api\ImportInterface;
use Magento\Catalog\Api\Data\ProductInterface as MagentoProductInterface;

/**
 * Interface ProductInterface
 *
 * @category  Lev
 * @package   Lev\Brewery\Api\Import
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface ProductInterface extends ImportInterface
{
    /**
     * Import single product
     *
     * @param MagentoProductInterface $product
     * @return ProductInterface
     */
    public function importProduct(MagentoProductInterface $product): ProductInterface;
}
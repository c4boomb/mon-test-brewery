<?php

namespace Lev\Brewery\Plugin;

use Closure;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;

class ProductGet
{
    protected $productExtensionFactory;
    protected $productFactory;

    public function __construct(
        ProductExtensionFactory $productExtensionFactory,
        ProductFactory $productFactory
    )
    {
        $this->productFactory = $productFactory;
        $this->productExtensionFactory = $productExtensionFactory;
    }

    public function aroundGetById(
        ProductRepositoryInterface $subject,
        Closure $proceed,
        $customerId
    )
    {
        /** @var ProductInterface $product */
        $product = $proceed($customerId);

        if ($product->getExtensionAttributes() && $product->getExtensionAttributes()->getFeatures()) {
            return $product;
        }

        if (!$product->getExtensionAttributes()) {
            $productExtension = $this->productExtensionFactory->create();
            $product->setExtensionAttributes($productExtension);
        }

        $productModel = $this->productFactory->create()->load($product->getId());
        $product->getExtensionAttributes()
            ->setDescription($productModel->getData('description'))
            ->setAbv($productModel->getData('abv'));

        return $product;
    }
}
<?php

namespace Lev\Brewery\Plugin;

use Closure;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;

/**
 * Class ProductGet
 *
 * @category  Lev
 * @package   Lev\Brewery\Plugin
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
class ProductGet
{
    /**
     * @var ProductExtensionFactory
     */
    protected $productExtensionFactory;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * ProductGet constructor
     *
     * @param ProductExtensionFactory $productExtensionFactory
     * @param ProductFactory $productFactory
     */
    public function __construct(
        ProductExtensionFactory $productExtensionFactory,
        ProductFactory $productFactory
    )
    {
        $this->productFactory = $productFactory;
        $this->productExtensionFactory = $productExtensionFactory;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param Closure $proceed
     * @param $customerId
     * @return ProductInterface
     */
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
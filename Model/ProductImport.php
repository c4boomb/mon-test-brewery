<?php

namespace Lev\Brewery\Model;

use Exception;
use InvalidArgumentException;
use Lev\Brewery\Api\Import\ProductInterface as ProductImportInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\WebsiteRepositoryInterface;

/**
 * Class ProductImport
 *
 * @category  Lev
 * @package   Lev\Brewery\Model
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
class ProductImport implements ProductImportInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var WebsiteRepositoryInterface
     */
    protected $websiteRepository;

    /**
     * ProductImport constructor
     *
     * @param ProductRepositoryInterface $productRepository
     * @param WebsiteRepositoryInterface $websiteRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, WebsiteRepositoryInterface $websiteRepository)
    {
        $this->productRepository = $productRepository;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * @param array $data
     * @return void
     * @throws InvalidArgumentException
     */
    public function import($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('$data should be array for product import');
        }

        /** @var ProductInterface $product */
        foreach ($data as $product) {
            try {
                $this->importProduct($product);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                //TODO: Logging error messages
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function importProduct(ProductInterface $product): ProductImportInterface
    {
        try {
            $existingProduct = $this->productRepository->get(
                $product->getSku()
            );

            $product->setId(
                $existingProduct->getId()
            );
            //TODO: Implement some hashing process to check if anything changed, saving everytime costs a lot of time
        } catch (NoSuchEntityException $ex) {
            //TODO: ProductInterface dont have addData method, should switch to Product model to improve
            $product->addData([
                'website_id' => $this->websiteRepository->getDefault()->getId(),
                'visibility' => ProductVisibility::VISIBILITY_BOTH,
                'attribute_set_id' => $product->getDefaultAttributeSetId(),
                'type_id' => ProductType::TYPE_SIMPLE,
                'stock_data' => [
                    'qty' => 100,
                    'is_in_stock' => true
                ]
            ]);
        }

        $this->productRepository->save($product);
        return $this;
    }
}
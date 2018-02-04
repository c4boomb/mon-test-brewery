<?php

namespace Lev\Brewery\Model\Response;

use Lev\Brewery\Api\ResponseInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Framework\DataObject;

class GetBeers extends DataObject implements ResponseInterface
{
    /**
     * Path to data in DataObject
     */
    const DATA_CURRENT_PAGE = 'currentPage';
    const DATA_NUM_OF_PAGES = 'numberOfPages';
    const DATA_PRODUCTS = 'products';

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * GetBeers constructor
     *
     * @param ProductInterfaceFactory $productFactory
     */
    public function __construct(ProductInterfaceFactory $productFactory)
    {
        $this->productFactory = $productFactory;
    }

    /**
     * Maps response from API to internal variables
     *
     * @param array $response
     * @return ResponseInterface
     */
    public function map(array $response): ResponseInterface
    {
        $this->setData(self::DATA_CURRENT_PAGE, $response['currentPage']);
        $this->setData(self::DATA_NUM_OF_PAGES, $response['numberOfPages']);

        if (!empty($response['data'])) {
            $this->mapProducts($response['data']);
        }
    }

    private function mapProducts(array $items) {
        $products = [];

        foreach ($items as $item) {
//            $productData = [
//                'sku' => $item['id'],
//                'name' => $item['name'],
//                'description' => $item['description'],
//                'abv' => $item['abv']
//            ];
//            $products[] = $this->productFactory->create()->setData($productData);
            $product = $this->productFactory->create()
                ->setSku($item['id'])
                ->setName($item['name']);
            $product->getExtensionAttributes()
                ->setDescription($item['description'])
                ->setAbv($item['abv']);
            $products[] = $product;
        }

        $this->setData(self::DATA_PRODUCTS, $products);
    }

    /**
     * @return string
     */
    public function getCurrentPage() : string {
        return $this->getData(self::DATA_CURRENT_PAGE);
    }

    /**
     * @return string
     */
    public function getNumberOfPages() : string {
        return $this->getData(self::DATA_NUM_OF_PAGES);
    }

    /**
     * @return string
     */
    public function getProducts() : string {
        return $this->getData(self::DATA_PRODUCTS);
    }
}
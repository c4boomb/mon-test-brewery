<?php

namespace Lev\Brewery\Model\Response;

use Lev\Brewery\Api\ResponseInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Framework\DataObject;

/**
 * Class GetBeers
 *
 * @category  Lev
 * @package   Lev\Brewery\Model\Response
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
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

        return $this;
    }

    /**
     * Map products to Product model
     *
     * @param array $items
     */
    private function mapProducts(array $items)
    {
        $products = [];

        foreach ($items as $item) {
            $product = $this->productFactory->create()
                ->setSku($item['id'])
                ->setName($item['name'])
                ->setPrice(5);
            $product->getExtensionAttributes()
                ->setDescription($item['description'] ?? '')
                ->setAbv($item['abv'] ?? 0.00);
            $products[] = $product;
        }

        $this->setData(self::DATA_PRODUCTS, $products);
    }

    /**
     * @return string
     */
    public function getCurrentPage(): string
    {
        return $this->getData(self::DATA_CURRENT_PAGE);
    }

    /**
     * @return string
     */
    public function getNumberOfPages(): string
    {
        return $this->getData(self::DATA_NUM_OF_PAGES);
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->getData(self::DATA_PRODUCTS);
    }
}
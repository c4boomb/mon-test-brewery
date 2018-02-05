<?php

namespace Lev\Brewery\Console\Command;

use Lev\Brewery\Api\ApiClientInterface;
use Lev\Brewery\Api\Import\ProductInterface as ProductImportInterface;
use Lev\Brewery\Api\Request\BuilderInterface as RequestBuilderInterface;
use Lev\Brewery\Model\Response\GetBeers;
use Lev\Brewery\Model\Response\GetBeersFactory as GetBeersResponseFactory;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportProductsCommand
 *
 * @category  Lev
 * @package   Lev\Brewery\Constole\Command
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
class ImportProductsCommand extends Command
{
    /**
     * @var State
     */
    private $state;

    /**
     * @var ProductImportInterface
     */
    private $productImport;

    /**
     * @var RequestBuilderInterface
     */
    private $requestBuilder;

    /**
     * @var ApiClientInterface
     */
    private $apiClient;

    /**
     * @var GetBeersResponseFactory
     */
    private $responseFactory;

    /**
     * ImportProductsCommand constructor
     *
     * @param ProductImportInterface $productImport
     * @param RequestBuilderInterface $requestBuilder
     * @param ApiClientInterface $apiClient
     * @param GetBeersResponseFactory $responseFactory
     * @param State $state
     * @internal param GetBeersResponse $response
     */
    public function __construct(
        ProductImportInterface $productImport,
        RequestBuilderInterface $requestBuilder,
        ApiClientInterface $apiClient,
        GetBeersResponseFactory $responseFactory,
        State $state
    )
    {
        $this->productImport = $productImport;
        $this->requestBuilder = $requestBuilder;
        $this->apiClient = $apiClient;
        $this->responseFactory = $responseFactory;
        $this->state = $state;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('brewery:import:products')->setDescription('Imports products from brewery.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode('adminhtml');
        $output->writeln('Product import started');
        $currentPage = 1;

        while (true) {
            $request = $this->requestBuilder->build('beers', 'GET', [
                'availableId' => 1,
                'p' => $currentPage
            ]);
            /** @var GetBeers $response */
            $response = $this->responseFactory->create();

            $this->apiClient->execute($request, $response);

            if (++$currentPage > $response->getNumberOfPages()) {
                break;
            }

            $this->productImport->import($response->getProducts());

            unset($response);
        }
        $output->writeln('Product import finished');
    }
}
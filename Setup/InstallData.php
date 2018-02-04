<?php

namespace Lev\Brewery\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(Product::ENTITY, 'abv', [
            'type' => 'float',
            'label' => 'ABV',
            'input' => 'text',
            'required' => false,
            'sort_order' => 100,
            'global' => ScopedAttributeInterface::SCOPE_STORE
        ]);

        $setup->endSetup();
    }
}
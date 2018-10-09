<?php

declare(strict_types=1);

namespace Setono\SyliusBulkSpecialsPlugin\Special\Factory;

use Setono\SyliusBulkSpecialsPlugin\Model\SpecialRuleInterface;
use Setono\SyliusBulkSpecialsPlugin\Special\Checker\Rule\ContainsProductRuleChecker;
use Setono\SyliusBulkSpecialsPlugin\Special\Checker\Rule\ContainsProductsRuleChecker;
use Setono\SyliusBulkSpecialsPlugin\Special\Checker\Rule\HasTaxonRuleChecker;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class SpecialRuleFactory
 */
class SpecialRuleFactory implements SpecialRuleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $decoratedFactory;

    /**
     * @param FactoryInterface $decoratedFactory
     */
    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): SpecialRuleInterface
    {
        return $this->decoratedFactory->createNew();
    }

    /**
     * @todo Transform switch to ServiceRegistry
     *
     * {@inheritdoc}
     */
    public function createByType(string $type, $configuration): SpecialRuleInterface
    {
        switch ($type) {
            case HasTaxonRuleChecker::TYPE:
                return $this->createHasTaxon((array) $configuration);
            case ContainsProductRuleChecker::TYPE:
                return $this->createContainsProduct((string) $configuration);
            case ContainsProductsRuleChecker::TYPE:
                return $this->createContainsProducts((array) $configuration);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createHasTaxon(array $taxons): SpecialRuleInterface
    {
        return $this->createSpecialRule(
            HasTaxonRuleChecker::TYPE,
            ['taxons' => $taxons]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createContainsProduct(string $productCode): SpecialRuleInterface
    {
        return $this->createSpecialRule(
            ContainsProductRuleChecker::TYPE,
            ['product_code' => $productCode]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createContainsProducts(array $productCodes): SpecialRuleInterface
    {
        return $this->createSpecialRule(
            ContainsProductsRuleChecker::TYPE,
            ['product_codes' => $productCodes]
        );
    }

    /**
     * @param string $type
     * @param array $configuration
     *
     * @return SpecialRuleInterface
     */
    private function createSpecialRule(string $type, array $configuration): SpecialRuleInterface
    {
        /** @var SpecialRuleInterface $rule */
        $rule = $this->createNew();
        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }
}

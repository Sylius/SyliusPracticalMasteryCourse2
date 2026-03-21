<?php

declare(strict_types=1);

namespace App\Cli;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Attribute\AttributeType\FloatAttributeType;
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test', description: 'Test Sylius Resource')]
class TestCommand extends Command
{
    public function __construct(
        private AttributeFactoryInterface $attributeFactory,
        private FactoryInterface $attributeValueFactory,
        private ProductRepositoryInterface $productRepository,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $attribute = $this->attributeFactory->createTyped(FloatAttributeType::TYPE);
        $attribute->setTranslatable(false);
        $attribute->setCode('float_attribute');
        $attribute->setName('Float Attribute');

        $this->em->persist($attribute);

        /** @var AttributeValueInterface $attributeValue */
        $attributeValue = $this->attributeValueFactory->createNew();
        $attributeValue->setAttribute($attribute);
        $attributeValue->setValue(3.14);

        $this->em->persist($attributeValue);

        $product = $this->productRepository->findOneByCode('Adventurous_Aurora_Cap');
        $product->addAttribute($attributeValue);

        $this->em->flush();

        return Command::SUCCESS;
    }
}

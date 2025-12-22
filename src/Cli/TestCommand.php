<?php

declare(strict_types=1);

namespace App\Cli;

use App\Entity\Brand\Brand;
use App\Entity\Product\Product;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test', description: 'Test Sylius Resource')]
class TestCommand extends Command
{
    public function __construct(
        private RepositoryInterface $brandRepository,
        private RepositoryInterface $productRepository,
        private EntityManagerInterface $brandManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $code = 'yamaha';
        $brand = $this->brandRepository->findOneBy([
            'code' => $code
        ]);

        if (!$brand instanceof Brand) {
            return Command::FAILURE;
        }

        $product = $this->productRepository->findOneBy([
            'code' => 'Ethereal_Drift_T_Shirt'
        ]);

        if (!$product instanceof Product) {
            return Command::FAILURE;
        }

        $brand->addProduct($product);

        $this->brandManager->flush();

        $output->writeln('Brand was associated to product ' . $product->getCode());

        return Command::SUCCESS;
    }
}

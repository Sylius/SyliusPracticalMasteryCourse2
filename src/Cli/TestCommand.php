<?php

declare(strict_types=1);

namespace App\Cli;

use App\Entity\Brand\Brand;
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
        private FactoryInterface $brandFactory,
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

        if ($brand instanceof Brand) {
            $output->writeln('Brand already exists');
            $output->writeln('Brand code: ' . $brand->getCode());
            $output->writeln('Brand name: ' . $brand->getName());

            return Command::SUCCESS;
        }

        /** @var Brand $brand */
        $brand = $this->brandFactory->createNew();
        $brand->setCode($code);
        $brand->setName(ucfirst($code));

        $this->brandManager->persist($brand);
        $this->brandManager->flush();

        $output->writeln('Brand was created');
        $output->writeln('Brand code: ' . $brand->getCode());
        $output->writeln('Brand name: ' . $brand->getName());

        return Command::SUCCESS;
    }
}

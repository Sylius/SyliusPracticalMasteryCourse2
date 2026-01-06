<?php

declare(strict_types=1);

namespace App\Cli;

use App\Entity\Brand\Brand;
use App\SM\BrandTransitions;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test', description: 'Test Sylius Resource')]
class TestCommand extends Command
{
    public function __construct(
        private RepositoryInterface $brandRepository,
        private StateMachineInterface $stateMachine,
        private EntityManagerInterface $brandManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Brand $brand */
        foreach ($this->brandRepository->findAll() as $brand) {
            if (false === $this->stateMachine->can($brand, BrandTransitions::GRAPH, BrandTransitions::TRANSITION_APPROVE)) {
                $output->writeln('Cannot apply transition to: ' . $brand->getName());

                continue;
            }

            $this->stateMachine->apply($brand, BrandTransitions::GRAPH, BrandTransitions::TRANSITION_APPROVE);
        }

        $this->brandManager->flush();
        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}

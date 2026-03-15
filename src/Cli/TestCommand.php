<?php

declare(strict_types=1);

namespace App\Cli;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Factory\TaxonFactoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonTranslationInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test', description: 'Test Sylius Resource')]
class TestCommand extends Command
{
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
        private TaxonFactoryInterface $taxonFactory,
        private FactoryInterface $taxonTranslationFactory,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parentCode = 'MENU_CATEGORY';
        $code = 'shoes';

        $parentTaxon = $this->taxonRepository->findOneBy(['code' => $parentCode]);
        if (!$parentTaxon instanceof TaxonInterface) {
            $output->writeln('Taxon not found');
            return Command::FAILURE;
        }

        $taxon = $this->taxonRepository->findOneBy(['code' => $code]);
        if ($taxon instanceof TaxonInterface) {
            $output->writeln('Taxon already exists');
            return Command::FAILURE;
        }

        $taxon = $this->taxonFactory->createForParent($parentTaxon);
        $taxon->setCode($code);

        /** @var TaxonTranslationInterface $taxonTranslation */
        $taxonTranslation = $this->taxonTranslationFactory->createNew();
        $taxonTranslation->setLocale('en_US');
        $taxonTranslation->setName('Shoes');
        $taxonTranslation->setSlug('shoes');
        $taxonTranslation->setDescription('Awesome shoes!');

        $taxon->addTranslation($taxonTranslation);

        $this->em->persist($taxon);
        $this->em->flush();

        return Command::SUCCESS;
    }
}

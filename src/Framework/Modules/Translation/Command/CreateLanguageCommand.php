<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Translation\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Papertowel\Framework\Entity\Website\Website;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateLanguageCommand extends Command
{
    protected static $defaultName = 'papertowel:language:create';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        return $this->addArgument('languageString')->addArgument('languageName');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $languageString = $input->getArgument('languageString');
        $languageName = $input->getArgument('languageName');

        $doctrine = $this->container->get('doctrine');

        $translationId = $this->container->get('doctrine')->getRepository(Translation::class)->getNewTranslationId();

        $language = new Language($languageString, null);

        $doctrine->getManager()->persist($language);
        $doctrine->getManager()->flush();

        $translation = new Translation($translationId, $language, $languageName);

        $doctrine->getManager()->persist($translation);
        $doctrine->getManager()->flush();

        $language->setName($translation);
        $doctrine->getManager()->persist($language);
        $doctrine->getManager()->flush();
    }
}

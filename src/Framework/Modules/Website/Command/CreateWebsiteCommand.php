<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Website\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Papertowel\Framework\Entity\Website\Website;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateWebsiteCommand extends Command
{
    protected static $defaultName = 'papertowel:website:create';

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
        return $this->addArgument('domain')->addArgument('websiteName')->addArgument('themeName')->addOption('parentDomain');
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
        $domain = $input->getArgument('domain');
        $websiteName = $input->getArgument('websiteName');
        $themeName = $input->getArgument('themeName');
        $parentDomain = $input->getOption('parentDomain');

        $doctrine = $this->container->get('doctrine');

        if ($domain === null || $websiteName === null || $themeName=== null) {
            throw new InvalidArgumentException();
        }

        $parent = null;
        if ($parentDomain !== null) {
            $parent = $this->container->get('doctrine')->getRepository(Website::class)->findOneBy(['domain' => $parentDomain]);
        }

        $translationId = $this->container->get('doctrine')->getRepository(Translation::class)->getNewTranslationId();
        $language = $this->container->get('doctrine')->getRepository(Language::class)->getDefaultLanguage();

        $translation = new Translation($translationId, $language, $websiteName);
        $website = new Website($domain, $themeName, $translation, $language, $parent);


        $doctrine->getManager()->persist($translation);
        $doctrine->getManager()->persist($website);
        $doctrine->getManager()->flush();
    }
}

<?php

namespace Papertowel\Framework\Repository\Language;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Papertowel\Framework\Entity\Translation\Language;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LanguageRepository extends EntityRepository
{
    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'id' => 1
        ]);
    }
}

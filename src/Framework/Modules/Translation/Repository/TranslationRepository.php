<?php

namespace Papertowel\Framework\Modules\Translation\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Papertowel\Framework\Modules\Translation\Entity\Language;
use Papertowel\Framework\Modules\Translation\Entity\Translation;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Translation::class);
    }

    /**
     * @param Language $language
     * @param int $id
     * @return Translation|null
     */
    public function getTranslationForLanguage(Language $language, int $id): ?Translation
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['translationId' => $id,
            'language' => $language,
        ]);
    }
}

<?php

namespace Papertowel\Framework\Repository\Language;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TranslationRepository extends EntityRepository
{

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

    public function getNewTranslationId(): ?int
    {
        $result = $this->createQueryBuilder('translation')
            ->select('MAX(translation.translationId)')->getQuery()->getResult()[0][1]; //TODO: Make this better

        return $result + 1;
    }
}

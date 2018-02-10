<?php

namespace Papertowel\Framework\Repository\Website;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Papertowel\Framework\Entity\Website\Website;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WebsiteRepository extends EntityRepository
{
    /**
     * @param string $domain
     * @return Website|null
     */
    public function findWebsiteByDomain(string $domain) : ?Website
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'domain' => $domain,
            'parent' => null
        ]);
    }

    /**
     * @param string $subDomain
     * @param string $domain
     * @return Website|null
     */
    public function findWebsiteBySubAndDomain(string $subDomain, string $domain) : ?Website
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'domain' => $subDomain,
            'parent' => $this->findWebsiteByDomain($domain)
        ]);
    }
}

<?php

namespace App\Repository;

use App\Entity\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WebsiteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Website::class);
    }


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

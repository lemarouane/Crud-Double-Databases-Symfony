<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
        $this->_em = $registry->getManager('authors');  // Use the 'authors' connection
    }

    public function findAllAuthors(): array
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }
}

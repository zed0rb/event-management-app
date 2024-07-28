<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Checks if a user is registered for a given event by email.
     *
     * @param int $eventId
     * @param string $email
     * @return bool
     */
    public function isUserRegisteredForEvent(int $eventId, string $email): bool
    {
        // Use the QueryBuilder to perform a direct database query
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.event = :eventId')
            ->andWhere('u.email = :email')
            ->setParameter('eventId', $eventId)
            ->setParameter('email', $email);

        // Execute the query and return true if there are results
        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}

<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * You should use the descriptive method name to indicate what the method does, for example "findByStatus"
     * You should add method docblock
     * You should use parameters to safely handle user input to avoid SQL injection. You can use Doctrine's query builder to achieve this
     * You can create the MessageStatusType enum and update the repository method to use it
     */
    public function by(Request $request): array
    {
        $status = $request->query->get('status');

        if ($status) {
            $messages = $this->getEntityManager()
                ->createQuery(
                    sprintf("SELECT m FROM App\Entity\Message m WHERE m.status = '%s'", $status)
                )
                ->getResult();
        } else {
            $messages = $this->findAll();
        }

        return $messages;

//        My code version

//        $qb = $this->createQueryBuilder('m');
//
//        if ($status && MessageStatusType::tryFrom($status)) {
//            $qb->andWhere('m.status = :status')
//                ->setParameter('status', MessageStatusType::from($status)->value);
//        }
//         return $qb->getQuery()->getResult();
    }
}

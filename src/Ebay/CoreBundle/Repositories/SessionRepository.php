<?php
namespace Ebay\CoreBundle\Repositories;

use Doctrine\ORM\EntityRepository;
use Ebay\CoreBundle\Entity\Person;

class SessionRepository extends EntityRepository
{

    public function getMoodForEmployee(Person $employee, \DateTime $dateFrom)
    {
        $queryBuilder = $this->createQueryBuilder('session')
            ->where('session.employee = :employee')
            ->andWhere('session.date > :date')
            ->setParameters(
                array('employee' => $employee,
                    'date' => $dateFrom
                ));

       return $queryBuilder->getQuery()->getArrayResult();

    }
}
<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
	private $manager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Customer::class);
		$this->manager = $manager;

    }

	public function addCustomer($FirstName, $LastName, $PhoneNumber, $Email){
		$customer=new Customer();
		$customer->SetFirstName($FirstName);
		$customer->SetLastName($LastName);
		$customer->SetPhoneNumber($PhoneNumber);
		$customer->SetEmail($Email);
		$this->manager->persist($customer);
        $this->manager->flush();
	}
	
	public function updateCustomer(Customer $customer):Customer{
		$this->manager->persist($customer);
        $this->manager->flush();
		return $customer;
	}
	
	public function deleteCustomer(Customer $customer){
		$this->manager->remove($customer);
        $this->manager->flush();
	}
	
    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

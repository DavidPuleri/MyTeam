<?php

namespace Ebay\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Person
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ebay\CoreBundle\Repositories\PersonRepository")
 */
class Person extends BaseUser
{
    const ROLE_DEFAULT = "ROLE_ADMIN";
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;
    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="employee")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id", nullable=true)
     */
    private $manager;
    /**
     * @ORM\OneToMany(targetEntity="Session", mappedBy="manager")
     */
    private $hostedSessions;
    /**
     * @ORM\OneToMany(targetEntity="Session", mappedBy="employee")
     */
    private $sessions;
    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="manager")
     */
    private $employee;
    private $filteredSession = array();

    public function __construct()
    {
        parent::__construct();
        $this->employee = new ArrayCollection();

    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @return Person
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $employee
     */
    public function addEmployee(Person $employee)
    {
        $this->employee->add($employee);
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function getName()
    {
        $name = $this->getFirstName() . ' ' . $this->getLastName();
        if (!trim($name)) {
            return $this->getUsername();
        }

        return $name;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostedSessions()
    {
        return $this->hostedSessions;
    }

    /**
     * @return mixed
     */
    public function getSessions(Person $manager)
    {
        $filteredSession = new ArrayCollection();
        foreach ($this->sessions as $session) {

            if ($session->getManager()->getId() === $manager->getId()) {
                $filteredSession->add($session);
            }
        }

        return $filteredSession;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString(){
        return $this->getName();
    }

}

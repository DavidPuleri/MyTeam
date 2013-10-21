<?php
namespace Ebay\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ebay\CoreBundle\Repositories\SessionRepository")
 */
class Session {


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="hostedSessions")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     */
    private $manager;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="sessions")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="mood", type="integer", length=11, nullable=true)
     */
    private $mood;

    public function __construct(Person $manager, Person $employee)
    {
        $this->manager = $manager;
        $this->employee = $employee;
    }

    /**
     * @return Person
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param string $note
     */
    public function setMood($note)
    {
        $this->mood = $note;
    }

    /**
     * @return string
     */
    public function getMood()
    {
        return $this->mood;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @return Person
     */
    public function getEmployee()
    {
        return $this->employee;
    }


}
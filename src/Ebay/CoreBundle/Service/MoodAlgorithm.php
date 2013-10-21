<?php
namespace Ebay\CoreBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Ebay\CoreBundle\Entity\Person;
use Ebay\CoreBundle\Repositories\SessionRepository;
use SplDoublyLinkedList;

class MoodAlgorithm
{

    /**
     * @var \Ebay\CoreBundle\Repositories\SessionRepository
     */
    private $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getMoodVarianceForEmployeeFromDate(Person $employee, \DateTime $dateFrom)
    {
        $moodForEmployee = $this->sessionRepository->getMoodForEmployee($employee, $dateFrom);

        if(count($moodForEmployee) == 0){
            return array();
        }
        $list = new \SplDoublyLinkedList();
        foreach ($moodForEmployee as $m) {
            $list->push($m["mood"]);
        }

        $list->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
        $previous = null;
        $index = 0;
        $moodList = new ArrayCollection();
        $variance = Mood::Neutral;

        for ($list->rewind(); $list->valid(); $list->next()) {

            if ($index > 0) {

                $prevIndex = $index - 1;
                $previous = $list->offsetGet($prevIndex);

                if ($previous < $list->current()) {
                    $variance = Mood::Positive;
                } else if($previous === $list->current()) {
                    $variance  = Mood::Neutral;
                }   else {
                    $variance  = Mood::Negative;
                }
            }
            if($index == 0) $variance = null;
            $index++;


            $moodList->add(new Mood($list->current(), $variance));

        }

        return $moodList;

    }
}

class Mood
{
    private $value;
    private $variance;
    const Neutral = "=";
    const Negative = "-";
    const Positive = "+";

    public function __construct($value, $variance)
    {
        $this->value = $value;
        $this->variance = $variance;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getVariance()
    {
        return $this->variance;
    }


}
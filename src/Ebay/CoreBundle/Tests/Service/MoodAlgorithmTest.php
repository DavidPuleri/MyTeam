<?php

namespace Ebay\CoreBundle\Tests\Service;
;

use Ebay\CoreBundle\Entity\Person;
use Ebay\CoreBundle\Service\MoodAlgorithm;
use SplDoublyLinkedList;
use Symfony\Component\Validator\Constraints\DateTime;

class MoodAlgorithmTest extends \PHPUnit_Framework_TestCase
{

    public function testGetLastMonth()
    {


        $sessionRepository = $this->getMockBuilder("Ebay\CoreBundle\Repositories\SessionRepository")
            ->disableOriginalConstructor()
            ->setMethods(array('getMoodForEmployee'))
            ->getMock();
        $employee = new Person();
        $employee->setId(10);

        $dateTime = new \DateTime();
        $sessionRepository->expects($this->once())
            ->method('getMoodForEmployee')
            ->with($employee, $dateTime)
            ->will($this->returnValue(array(
                array("mood"=>3),
                array("mood"=>5),
                array("mood"=>7),
                array("mood"=>8),
                array("mood"=>10),
                array("mood"=>7),
                array("mood"=>7)
                )
            ));

        $service = new MoodAlgorithm($sessionRepository);

        $moods = $service->getMoodVarianceForEmployeeFromDate($employee, $dateTime);
        $this->assertCount(7, $moods);
        $this->assertEquals(3, $moods->get(0)->getValue());
        $this->assertEquals("=", $moods->get(0)->getVariance());

        $this->assertEquals(5, $moods->get(1)->getValue());
        $this->assertEquals("+", $moods->get(1)->getVariance());

        $this->assertEquals(7, $moods->get(2)->getValue());
        $this->assertEquals("+", $moods->get(2)->getVariance());

        $this->assertEquals(8, $moods->get(3)->getValue());
        $this->assertEquals("+", $moods->get(3)->getVariance());

        $this->assertEquals(10, $moods->get(4)->getValue());
        $this->assertEquals("+", $moods->get(4)->getVariance());

        $this->assertEquals(7, $moods->get(5)->getValue());
        $this->assertEquals("-", $moods->get(5)->getVariance());

        $this->assertEquals(7, $moods->get(6)->getValue());
        $this->assertEquals("=", $moods->get(6)->getVariance());
    }
}
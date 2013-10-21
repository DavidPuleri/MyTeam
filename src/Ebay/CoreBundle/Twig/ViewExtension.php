<?php
namespace Ebay\CoreBundle\Twig;


use Ebay\CoreBundle\Entity\Person;
use Ebay\CoreBundle\Service\MoodAlgorithm;

class ViewExtension extends \Twig_Extension {


    /**
     * @var \Ebay\CoreBundle\Service\MoodAlgorithm
     */
    private $moodAlgorithm;

    public function __construct(MoodAlgorithm $moodAlgorithm)
    {
        $this->moodAlgorithm = $moodAlgorithm;
    }
    public function getFunctions()
    {
        return array(
            'moodTrend' => new \Twig_Function_Method($this, 'moodTrend'),
        );
    }

    public function moodTrend(Person $employee,\DateTime $dateFrom)
    {

        $arrayCollection = $this->moodAlgorithm->getMoodVarianceForEmployeeFromDate($employee, $dateFrom);
        $string = null;


        foreach($arrayCollection as $a){
           $string .= $a->getVariance() . ' '.$a->getValue().' ';
        }
        return $string;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
       return "view_extension";
    }
}
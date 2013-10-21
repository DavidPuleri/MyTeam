<?php
namespace Ebay\CoreBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SessionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("date");
        $builder->add("mood", "choice", array("choices" => array(1=>1, 2=>2,3=> 3,4=> 4,5=> 5,6=> 6,7=> 7,8=> 8,9=> 9, 10=>10)));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'session_form_type';
    }
}
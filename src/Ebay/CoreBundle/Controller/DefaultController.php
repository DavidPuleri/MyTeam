<?php

namespace Ebay\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Ebay\CoreBundle\Entity\Person;
use Ebay\CoreBundle\Entity\Session  as OneToOneSession;
use Ebay\CoreBundle\Form\SessionType;
use Ebay\CoreBundle\Repositories\PersonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @Route(service="controller.default")
 */
class DefaultController
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var \Ebay\CoreBundle\Repositories\PersonRepository
     */
    private $personRepository;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    function __construct(SecurityContextInterface $securityContext, FormFactoryInterface $formFactory, PersonRepository $personRepository, Router $router, ObjectManager $objectManager)
    {
        $this->securityContext = $securityContext;
        $this->formFactory = $formFactory;
        $this->personRepository = $personRepository;
        $this->router = $router;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $dateFrom = new \DateTime("- 1 month");
        return array('user' => $this->getUser(), 'dateFrom' => $dateFrom);
    }

    /**
     * @return Person
     */
    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * @Route("/session/{employeeId}/new", name="session.new")
     * @Template()
     */
    public function sessionAction(Request $request, $employeeId)
    {

        $manager = $this->getUser();
        $employee = $this->personRepository->find($employeeId);
        $session = new OneToOneSession($manager, $employee);
        $session->setDate(new \DateTime());
        $form = $this->formFactory->create(new SessionType(), $session
            , array(
                'action' => $this->router->generate('session.new', array("employeeId" => $employeeId)),
                'method' => 'POST',
                'attr' => array('class'=>'form-horizontal',  'role'=>'form')
            ));

        $form->add("Save", "submit", array('attr' => array('class'=>'btn btn-default')));

        $form->handleRequest($request);
        if ($form->isValid()) {

            $this->objectManager->persist($session);
            $this->objectManager->flush();

            return new RedirectResponse($this->router->generate('homepage'));
        }

        return array('form' => $form->createView(),"employee"=> $employee);
    }


}

<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     *
     * @Route("/login", name="usuario_login")
     * @Route("/login_check", name="usuario_login_check")
     * @Template("WasdBestnidBundle:usuario:login.html.twig")
     */
    public function loginAction()
    {
        $peticion =  $this->getRequest();
        $sesion = $peticion->getSession();
        
        if ($peticion->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $sesion->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        );
    }
}

<?php
namespace Group4\BaseBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        // Menu will be a navbar menu anchored to right
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class','navbar nav');
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild($user->getUsername(),array('route' => 'fos_user_profile_show'));
            $menu->addChild('Sign out', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('Sign in', array('route' => 'fos_user_security_login'));
            $menu->addChild('Sign up', array('route' => 'fos_user_registration_register'));
        }


        // Add a regular child with an icon, icon- is prepended automatically
        return $menu;
    }
}
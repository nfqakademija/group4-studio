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
        $menu->setAttribute('class','nav');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('User', array('label' => $user->getUsername()))
                ->setAttribute('dropdown', true);
            $menu['User']->addChild('Sign out', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('Sign in', array('route' => 'fos_user_security_login'));
            $menu->addChild('Sign up', array('route' => 'fos_user_registration_register'));
        }


        // Add a regular child with an icon, icon- is prepended automatically
        return $menu;
    }
}
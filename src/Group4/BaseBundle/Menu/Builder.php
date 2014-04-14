<?php
namespace Group4\BaseBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        // Menu will be a navbar menu anchored to right
        $menu = $factory->createItem('root', array(
            'navbar' => true,
            'pull-right' => true,
        ));

        // Add a regular child with an icon, icon- is prepended automatically
        $layout = $menu->addChild('Layout', array(
            'icon' => 'home',
        ));

        // Create a dropdown with a caret
        $dropdown = $menu->addChild('Forms', array(
            'dropdown' => true,
            'caret' => true,
        ));

        // Create a dropdown header
        $dropdown->addChild('Some Header', array('dropdown-header' => true));
        $dropdown->addChild('Example 1');

        return $menu;
    }
}
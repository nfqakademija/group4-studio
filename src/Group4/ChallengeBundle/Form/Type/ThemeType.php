<?php
namespace Group4\ChallengeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('Create theme', 'submit');
    }

    public function getName()
    {
        return 'theme';
    }
}

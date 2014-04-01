<?php
namespace Group4\ChallengeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('status')
        ->add('startDate')
        ->add('endDate')
        ->add('type')
        ->add('themeId')
        ->add('Create challenge', 'submit');
    }

    public function getName()
    {
        return 'challenge';
    }
}

<?php

namespace Group4\ChallengeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('image', 'file',
                array(
                    'label' => 'Select the photo you want to upload'
                )
            )
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Group4\ChallengeBundle\Entity\Photo'
        ));
    }

    public function getName() {
        return "UploadBundle";
    }

}
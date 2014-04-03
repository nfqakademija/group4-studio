<?php
namespace Group4\ChallengeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChallengeType extends AbstractType
{
    private $themeIds;
    private $themeNames;
    public function __construct(array $themes)
    {
        foreach($themes as $t){
            $this->themeNames[]=$t->getName();
        }
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('status','choice',array('choices' => array('Not started','Shooting/Uploading','Voting','Ended')))
        ->add('startDate')
        ->add('endDate')
        ->add('type','text')
        ->add('themeId','choice',array('choices' => array($this->themeNames),'required' => true))
        ->add('Create challenge', 'submit');
    }

    public function getName()
    {
        return 'challenge';
    }
}

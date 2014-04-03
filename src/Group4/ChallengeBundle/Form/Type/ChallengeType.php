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
            $themeNames[]=$t->getName();
            //$themeApproveds[]=$t->getThemeApproved;
        }
        $this->themeNames = $themeNames;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('status','choice',array('choices' => array('Not started','Shooting/Uploading','Voting','Ended')))
        ->add('startDate')
        ->add('endDate')
        ->add('type')
        ->add('themeId','choice',array('choices' => array($this->themeNames),'required' => true))
        ->add('Create challenge', 'submit');
    }

    public function getName()
    {
        return 'challenge';
    }
}

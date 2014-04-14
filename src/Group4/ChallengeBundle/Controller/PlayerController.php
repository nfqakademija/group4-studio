<?php

namespace Group4\ChallengeBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Group4\ChallengeBundle\Entity\PlayerToChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;

class PlayerController extends Controller
{
    public function joinChallengeFormAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('type', 'choice', array(
                'choices'   => array('1' => '5 minutes', '2' => '15 minutes'),
                'required'  => true,
            ))
            ->add('Start Challenge', 'submit')
            ->setAction($this->generateUrl("challenge_me"))
            ->getForm();

        $form->handleRequest($request);

        if ('POST' === $request->getMethod()) {
            if ($form->isValid()) {
                $type = $form->get('type')->getData();

                $repository = $this->getDoctrine()
                    ->getRepository('ChallengeBundle:Challenge');

                $challenge = $repository->getActiveChallenge($type);
                $repository = $this->getDoctrine()
                    ->getRepository('ChallengeBundle:Theme');

                $user = $this->getUser();

                if (!empty($challenge) && !$challenge->isInChallenge($user)) {
                    $playerToChallenge = new PlayerToChallenge();
                    $playerToChallenge->setStatus(0)
                        ->setUser($user)
                        ->setDate(new \DateTime("now"));
                    $challenge->addPlayerToChallenge($playerToChallenge);
                } else {
                    $themes = array();
                    $themes = $repository->findByApproved(true);
                    $challenge = new Challenge($themes[rand(0,count($themes)-1)]->getId(),$type);
                    $playerToChallenge = new PlayerToChallenge();
                    $playerToChallenge->setStatus(0)
                        ->setUser($user)
                        ->setDate(new \DateTime("now"));
                    $challenge->addPlayerToChallenge($playerToChallenge);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($challenge);
                $em->flush();
            }
        }

        return $this->render('ChallengeBundle:Default:JoinChallengeForm.html.twig', array('form' => $form->createView()));
    }

    public function uploadAction(Request $request) {

        $photo = new Photo();
        $form = $this->createForm(new UploadFormType(), $photo);

        if('POST' === $request->getMethod()) {
            $form->bind($request);

            if($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $photo->setUserId($this->container->get('security.context')->getToken()->getUser()->getId());
                $em->persist($photo);
                $em->flush();

                return $this->render('ChallengeBundle:Player:successUpload.html.twig', array('photo'=>$photo));
            }

        }

        return $this->render('ChallengeBundle:Player:upload.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}

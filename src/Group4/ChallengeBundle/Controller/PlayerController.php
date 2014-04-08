<?php

namespace Group4\ChallengeBundle\Controller;


use Group4\ChallengeBundle\Entity\PlayerToChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;

class PlayerController extends Controller
{
    public function JoinChallengeFormAction(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('Type', 'choice', array(
                'choices'   => array('1' => '5 minutes', '2' => '15 minutes'),
                'required'  => true,
            ))
            ->add('Start Challenge', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $type = $form->get('Type')->getData();

            $Repository = $this->getDoctrine()
                ->getRepository('ChallengeBundle:Challenge');

            $query = $Repository->createQueryBuilder('p')
                ->setParameter('status', '1')
                ->setParameter('type', $type)
                ->orderBy('p.date', 'ASC')
                ->getQuery();

            $challenges = $query->getResult();

            $Repository = $this->getDoctrine()
                ->getRepository('ChallengeBundle:Theme');

            $user = $this->container->get('security.context')->getToken()->getUser();

            if (!empty($challenges)) {
                $playerToChallenge = new PlayerToChallenge();
                $playerToChallenge->setStatus(0)
                    ->setUser($user)
                    ->setDate(date("Y-m-d H:i:s"))
                    ->setChallenge($challenges[0]);
                $em = $this->getDoctrine()->getManager();
                $em->persist($playerToChallenge);
                $em->flush();

            } else {
                $themes = $Repository->listAll();
                $challenge = new Challenge();
                $challenge->setStatus(1);
                $challenge->setStartDate(date("Y-m-d H:i:s"));
                $challenge->setEndDate(date("Y-m-d H:i:s", strtotime("+2 days")));
                $challenge->setThemeId(rand(1,count($themes)));
                $challenge->setType($type);

                $em = $this->getDoctrine()->getManager();
                $em->persist($challenge);
                $em->flush();

                $playerToChallenge = new PlayerToChallenge();
                $playerToChallenge->setStatus(0)
                    ->setUser($user)
                    ->setDate(date("Y-m-d H:i:s"))
                    ->setChallenge($challenge);

                $em->persist($playerToChallenge);
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
                $photo->setUserId($this->getUser()->getId());
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

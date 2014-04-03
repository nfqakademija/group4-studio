<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;


class PlayerController extends Controller
{
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
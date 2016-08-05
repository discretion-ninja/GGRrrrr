<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Score;

class GameController extends Controller
{
    /**
     * @Route("/game/{id}", name="game")
     */
    public function gameAction($id)
    {
        // replace this example code with whatever you need
        return $this->render('default/game.html.twig', array('id'=>$id));
    }

    /**
     * @Route("/challengelist", name="challengelist")
     */
    public function challengeListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $challenges = $em->getRepository('AppBundle:Challenge')->findAll();
        dump($challenges);
        return $this->render('default/challengelist.html.twig', array(
            'challenges' => $challenges,
        ));
    }

    /**
     * @Route("/score/new", options = { "expose" = true }, name="scoreNew")
     */
    public function scoreAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $score = new Score();

            $totalScore = $request->request->get('score');
            $idChallenge = $request->request->get('id');

            $user = $this->getUser();
            $challenge = $em->getRepository('AppBundle:Challenge')->findOneById($idChallenge);

            $score->setScore($totalScore);
            $score->setIdChallenge($challenge);
            $score->setIdUser($user);

            $em->persist($score);
            $em->flush();

            return $this->redirectToRoute('score');
            // $response = new Response($score);
            // return $response;
        }
    }

    /**
     * @Route("/score", options = { "expose" = true }, name="score")
     */
    public function scoreListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $scores = $em->getRepository('AppBundle:Score')->findAll();
        dump($scores);
        
        // $a = new ArrayCollection();
        // $a->add('challengeName'.','.'userName'.','.'userScore');

        // foreach ($scores as $score) {
        //     $userScore = $score->getScore();
        //     $userId = $score->getIdUser()->getUserName();
        //     $challengeId = $score->getIdChallenge()->getName();

        //     $a->add($challengeId.','.$userId.','.$userScore);
        //     $arr = $a->toArray();
        // }

        return $this->render('default/score.html.twig', array('scoresTab'=>$arr));
    }
}

<?php

/**
 * RatingController class file
 *
 * PHP Version 7.1
 *
 * @category RatingController
 * @package  RatingController
 * @author   HumanAid <contact.humanaid@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://example.com/
 */

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * RatingController class
 *
 * The class holding the root RatingController class definition
 *
 * @category RatingController
 * @package  RatingController
 * @author   HumanAid <contact.humanaid@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://example.com/
 * @Route("/rating")
 */
class RatingController extends AbstractController
{
    /**
     * @Route("/", name="rating_index", methods={"GET"})
     * @param RatingRepository $ratingRepository
     * @return Response
     */
    public function index(RatingRepository $ratingRepository): Response
    {
        return $this->render('rating/index.html.twig', [
            'ratings' => $ratingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rating_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('rating_index');
        }

        return $this->render('rating/new.html.twig', [
            'rating' => $rating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rating_show", methods={"GET"})
     * @param Rating $rating
     * @return Response
     */
    public function show(Rating $rating): Response
    {
        return $this->render('rating/show.html.twig', [
            'rating' => $rating,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rating_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Rating $rating
     * @return Response
     */
    public function edit(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rating_index');
        }

        return $this->render('rating/edit.html.twig', [
            'rating' => $rating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rating_delete", methods={"DELETE"})
     * @param Request $request
     * @param Rating $rating
     * @return Response
     */
    public function delete(Request $request, Rating $rating): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rating->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rating_index');
    }
}

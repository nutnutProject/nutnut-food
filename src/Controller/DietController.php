<?php

namespace App\Controller;

use App\Entity\Diet;
use App\Form\DietType;
use App\Repository\DietRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diet")
 */
class DietController extends AbstractController
{
    /**
     * @Route("/", name="diet_index", methods={"GET"})
     */
    public function index(DietRepository $dietRepository): Response
    {
        return $this->render('diet/index.html.twig', [
            'diets' => $dietRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="diet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $diet = new Diet();
        $form = $this->createForm(DietType::class, $diet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diet);
            $entityManager->flush();

            return $this->redirectToRoute('diet_index');
        }

        return $this->render('diet/new.html.twig', [
            'diet' => $diet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diet_show", methods={"GET"})
     */
    public function show(Diet $diet): Response
    {
        return $this->render('diet/show.html.twig', [
            'diet' => $diet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="diet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Diet $diet): Response
    {
        $form = $this->createForm(DietType::class, $diet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('diet_index');
        }

        return $this->render('diet/edit.html.twig', [
            'diet' => $diet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Diet $diet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($diet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('diet_index');
    }
}

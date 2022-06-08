<?php

namespace App\Controller\Admin;

use App\Entity\Espacio;
use App\Form\EspacioType;
use App\Repository\EspacioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/espacio")
 */
class EspacioController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_espacio_index", methods={"GET"})
     */
    public function index(EspacioRepository $espacioRepository): Response
    {
        return $this->render('admin/espacio/index.html.twig', [
            'espacios' => $espacioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_espacio_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EspacioRepository $espacioRepository): Response
    {
        $espacio = new Espacio();
        $form = $this->createForm(EspacioType::class, $espacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $espacioRepository->add($espacio, true);

            return $this->redirectToRoute('app_admin_espacio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/espacio/new.html.twig', [
            'espacio' => $espacio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_espacio_show", methods={"GET"})
     */
    public function show(Espacio $espacio): Response
    {
        return $this->render('admin/espacio/show.html.twig', [
            'espacio' => $espacio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_espacio_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Espacio $espacio, EspacioRepository $espacioRepository): Response
    {
        $form = $this->createForm(EspacioType::class, $espacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $espacioRepository->add($espacio, true);

            return $this->redirectToRoute('app_admin_espacio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/espacio/edit.html.twig', [
            'espacio' => $espacio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_espacio_delete", methods={"POST"})
     */
    public function delete(Request $request, Espacio $espacio, EspacioRepository $espacioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$espacio->getId(), $request->request->get('_token'))) {
            $espacioRepository->remove($espacio, true);
        }

        return $this->redirectToRoute('app_admin_espacio_index', [], Response::HTTP_SEE_OTHER);
    }
}

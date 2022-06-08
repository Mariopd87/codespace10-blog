<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categoria")
 */
class CategoriaController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_categoria_index", methods={"GET"})
     */
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        return $this->render('admin/categoria/index.html.twig', [
            'categorias' => $categoriaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_categoria_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoriaRepository $categoriaRepository): Response
    {
        $categorium = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categorium, true);

            return $this->redirectToRoute('app_admin_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categoria_show", methods={"GET"})
     */
    public function show(Categoria $categorium): Response
    {
        return $this->render('admin/categoria/show.html.twig', [
            'categorium' => $categorium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_categoria_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categoria $categorium, CategoriaRepository $categoriaRepository): Response
    {
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categorium, true);

            return $this->redirectToRoute('app_admin_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categoria_delete", methods={"POST"})
     */
    public function delete(Request $request, Categoria $categorium, CategoriaRepository $categoriaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorium->getId(), $request->request->get('_token'))) {
            $categoriaRepository->remove($categorium, true);
        }

        return $this->redirectToRoute('app_admin_categoria_index', [], Response::HTTP_SEE_OTHER);
    }
}

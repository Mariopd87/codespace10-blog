<?php

namespace App\Controller\Admin;

use App\Entity\Entrada;
use App\Form\EntradaType;
use App\Repository\EntradaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/admin/entrada")
 */
class EntradaController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_entrada_index", methods={"GET"})
     */
    public function index(EntradaRepository $entradaRepository): Response
    {
        return $this->render('admin/entrada/index.html.twig', [
            'entradas' => $entradaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_entrada_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntradaRepository $entradaRepository, UsuarioRepository $usuarioRepository): Response
    {
        $entrada = new Entrada();
        $form = $this->createForm(EntradaType::class, $entrada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $usuarioRepository->find(1);
            $entrada->setUsuario($usuario);
            $entrada->setFecha(new \DateTime());
            $slugger = new AsciiSlugger();
            $entrada->setSlug(strtolower($slugger->slug($entrada->getTitulo())) . '-' . uniqid());
            $entradaRepository->add($entrada, true);

            return $this->redirectToRoute('app_admin_entrada_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/entrada/new.html.twig', [
            'entrada' => $entrada,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_entrada_show", methods={"GET"})
     */
    public function show(Entrada $entrada): Response
    {
        return $this->render('admin/entrada/show.html.twig', [
            'entrada' => $entrada,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_entrada_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Entrada $entrada, EntradaRepository $entradaRepository): Response
    {
        $form = $this->createForm(EntradaType::class, $entrada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();
            $entrada->setSlug(strtolower($slugger->slug($entrada->getTitulo())) . '-' . uniqid());

            $entradaRepository->add($entrada, true);

            return $this->redirectToRoute('app_admin_entrada_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/entrada/edit.html.twig', [
            'entrada' => $entrada,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_entrada_delete", methods={"POST"})
     */
    public function delete(Request $request, Entrada $entrada, EntradaRepository $entradaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entrada->getId(), $request->request->get('_token'))) {
            $entradaRepository->remove($entrada, true);
        }

        return $this->redirectToRoute('app_admin_entrada_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/usuario")
 */
class UsuarioController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_usuario_index", methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('admin/usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_usuario_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request, 
        UsuarioRepository $usuarioRepository,
        UserPasswordHasherInterface $passwordHasher
        ): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $usuario,
                $usuario->getPassword()
            );
            $usuario->setPassword($hashedPassword);
            $usuarioRepository->add($usuario, true);

            return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_usuario_show", methods={"GET"})
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('admin/usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_usuario_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request, 
        Usuario $usuario,
        UsuarioRepository $usuarioRepository,
        UserPasswordHasherInterface $passwordHasher
        ): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $usuario,
                $usuario->getPassword()
            );
            $usuario->setPassword($hashedPassword);
            $usuarioRepository->add($usuario, true);

            return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_usuario_delete", methods={"POST"})
     */
    public function delete(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $usuarioRepository->remove($usuario, true);
        }

        return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formation")
 */
class FormationController extends Controller
{
    /**
     * @Route("/", name="formation_index", methods="GET", schemes={"%secure_channel%"})
     * @param FormationRepository $formationRepository
     * @return Response
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', ['formations' => $formationRepository->findAll()]);
    }

    /**
     * @Route("/new", name="formation_new", methods="GET|POST", schemes={"%secure_channel%"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_show", methods="GET", schemes={"%secure_channel%"})
     * @param Formation $formation
     * @return Response
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', ['formation' => $formation]);
    }

    /**
     * @Route("/{id}/edit", name="formation_edit", methods="GET|POST", schemes={"%secure_channel%"})
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    public function edit(Request $request, Formation $formation): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_delete", methods="DELETE", schemes={"%secure_channel%"})
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    public function delete(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formation);
            $em->flush();
        }
        return $this->redirectToRoute('formation_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController
 * @package App\Controller
 *
 * @Route("authors", name="author_")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author")
     */
    public function index()
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function showAction(Author $author){
        $data = $this->get('serializer')->serializer($author, "json");

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("create", name="create", methods={"POST"})
     */
    public function createAction(Request $request){
        $data = $request->getContent();

        $author = $this->get('serializer')->deserializer($data, 'App\Entity\Author', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->flush($author);
        $em->flush();
        return new Response('', Response::HTTP_CREATED);
    }

}

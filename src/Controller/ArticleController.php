<?php

namespace App\Controller;

use App\Entity\Article;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/article", name="article_")
 */
class ArticleController extends AbstractController
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function createAction(Request $request){
        $data = $request->getContent();
        $article = $this->serializer->deserialize($data, 'App\Entity\Article', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function showAction(Article $article){
        $data = $this->serializer->serialize($article, 'json', SerializationContext::create()->setGroups(array('detail')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/show/{limit}", name="showAll", methods={"GET"}, requirements={"limit"="\d+"})
     */
    public function getAction($limit = 5){
        $articles = $this->getDoctrine()->getRepository('App:Article')->findBy(array(), array(), $limit);

        $data = $this->serializer->serialize($articles, 'json', SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("delete/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();

    }
}

<?php


namespace App\Controller;


use App\Entity\Article;
use App\Representation\Articles;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\TraceableValidator;

class ArticlController extends AbstractFOSRestController
{
    private $serializer;
    private $validator;
    public function __construct(Serializer $serializer, TraceableValidator $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     *
     * @Rest\Get(
     *     path= "/api/articles/{id}",
     *     name= "show_article",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View
     */
    public function showAction(Article $article){
        return $article;
    }

    /**
     * @Rest\Post(
     *     path = "/api/article/create",
     *     name = "create_article"
     * )
     *
     * @Rest\View
     * @ParamConverter(
     *     "article",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={"groups"="Create"}
     *       }
     *
     * )
     */
    public function createAction(Article $article, ConstraintViolationList $violations){

        /*$data = $this->serializer->deserialize($request->getContent(), 'array', 'json');

        $article = new Article();

        $form = $this->get('form.factory')->create(ArticleType::class, $article);
        $form->submit($data);*/

        if(count($violations)){
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl("show_article", ["id" => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }

    /**
     * @Rest\Get(
     *     path = "/api/articles/list",
     *     name = "list_articles",
     * )
     * @Rest\QueryParam(
     *     name = "order",
     *     requirements = "asc|desc",
     *     default = "asc",
     *     description = "Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name = "limit",
     *     requirements = "\d+",
     *     default = "15",
     *     description = "Max number of articles per pages"
     * )
     * @Rest\QueryParam(
     *     name = "keyword",
     *     requirements = "[a-zA-Z0-9]",
     *     nullable = false,
     *     description = "The word search for"
     * )
     * @Rest\QueryParam(
     *     name = "offset",
     *     requirements = "\d+",
     *     default = "0",
     *     description = "The pagination offset"
     * )
     *
     *
     * @Rest\View()
     */
    public function listAction(ParamFetcherInterface $paramFetcher){
        //$articles = $this->getDoctrine()->getRepository('App:Article')->findAll(['title'=>$order]);

        $pager = $this->getDoctrine()->getRepository('App:Article')->search(
            $paramFetcher->get("keyword"),
            $paramFetcher->get("order"),
            $paramFetcher->get("limit"),
            $paramFetcher->get("offset"));

        return new Articles($pager);
    }
}

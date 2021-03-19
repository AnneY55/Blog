<?php

namespace App\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(LoggerInterface $logger): Response
    {
    	
    	$bdd_article = $this->getDoctrine()
            ->getRepository(article::class)
            ->findAll();
        return $this->render('article/list.html.twig', [
            'title'=> 555,
            'subtitle' => "la vie animalière",
            'description' => "une longue description de chat et de chaton",
            'article' => $bdd_article,

        ]);


    }

    /**
     * @Route("/article/{id}", name="detail")
     */
    public function detail(int $id): Response
    {
    	
    	$bdd_article = $this->getDoctrine()
            ->getRepository(article::class)
            ->find($id);
        return $this->render('article/detail.html.twig', [
             'article' => $bdd_article,

        ]);
    }
	/**
     * @Route("/articlecreate", name="create")
    */
	public function create(Request $request, LoggerInterface $logger):Response
		{
			 $form = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
        ))
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('image', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Article'])
            //->add('submit', 'submit')
            ->getForm();

             if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            // data is an array with "title", "image", and "description" keys
            $data = $form->getData();
            $logger->info($data["title"]);
            $logger->info($data["image"]);
            $logger->info($data["description"]);
            $entityManager = $this->getDoctrine()->getManager();
            $article = new Article();
            $article->setImage($data["image"]);
            $article->setTitle($data["title"]);
            $article->setDescription($data["description"]);
            $entityManager->persist($article);
            $entityManager->flush();
        }
	

    return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
}
}



<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("category/new" ,name="category")
     */
    public function new(Request $request,ManagerRegistry $managerRegistry){
        $category =new Category();
        $form= $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $managerRegistry->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash(
                'success',
                "Bien Enregistrer"
            );
            return $this->redirectToRoute('article');


        }

        return $this->render('category/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/art_cat/",name="article_par_cat")
     */
    public function index(Request $request){
        $CategorySearch =new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$CategorySearch);
        $form->handleRequest($request);
        $articles=[];
        if($form->isSubmitted() && $form->isValid()) {
            $category=$CategorySearch->getCategory();
            if($category!="")
                $articles=$category->getArticles();
            else
                $articles=$this->getDoctrine()->getRepository(Article::Class)->findAll();

        }
        return $this->render('article/articleParCategory.html.twig',[
            'form' => $form->createView(),'articles'=>$articles
        ]);
    }
}

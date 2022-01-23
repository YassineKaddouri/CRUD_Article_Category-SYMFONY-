<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\PriceSearch;
use App\Entity\PropertSearch;
use App\Form\AddformType;
use App\Form\CategoryType;
use App\Form\PriceSearchType;
use App\Form\PropertySearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\component\Form\Extension\core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{

    /**
     * Permet de creer une annonce
     *
     * @Route("/article/save",name="new")
     * Method({"GET","POST"})
     * @return Response
     */
    public function save(Request $request,ManagerRegistry $managerRegistry){
        $article =new Article();
        $form= $this->createForm(AddformType::class,$article);
         $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $managerRegistry->getManager();
            $em->persist($article);
            $em->flush();
            $this->addFlash(
                'success',
                "Bien Enregistrer"
            );
            return $this->redirectToRoute('article');


        }

        return $this->render('article/add.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/article/{id}/Delete",name="DeleteArticle")
     * @return Response
     */
    public function delete(Article $article,ManagerRegistry $managerRegistry){
        $em = $managerRegistry->getManager();
        $em->remove($article);
        $em->flush();
        $this->addFlash(
            'success',
            'Bien supprimer'
        );
        return $this->redirectToRoute('article');

    }

        /**
         * Permet d'afficher le formulaire d'edition
         * @Route("/article/{id}/edit",name="article_edit")
         * @return Response
         */
        public function edit(Article $article,Request  $request,ManagerRegistry $managerRegistry){

            $form =$this->createForm(AddformType::class,$article);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                $em = $managerRegistry->getManager();
                $em->persist($article);
                $em->flush();
                $this->addFlash(
                    'success',
                    "Les modifications de L'article bien été enregestée!"
                );

                return $this->redirectToRoute('article');

            }
            return $this->render('article/edit.html.twig',[
                'form' => $form->createView()
            ]);


        }

    /**
     * @Route("/article", name="article")
     */
        public function index(Request $request){
            $propertySearch =new PropertSearch();
            $form = $this->createForm(PropertySearchType::class,$propertySearch);
            $form->handleRequest($request);
            $articles=[];
            if($form->isSubmitted() && $form->isValid()) {
                $nom=$propertySearch->getNom();
                if($nom!="")
                $articles=$this->getDoctrine()->getRepository(Article::Class)->findBy(['nom'=>$nom]);
                else
                $articles=$this->getDoctrine()->getRepository(Article::Class)->findAll();


            }
            return $this->render('article/index.html.twig',[
                'form' => $form->createView(),'articles'=>$articles
            ]);
            }
//price
    /**
     * @Route("/art_prix/", name="article_par_prix")
     * Method({"GET"})
     */
    public function articlesParPrix(Request $request)
    {

        $priceSearch = new PriceSearch();
        $form = $this->createForm(PriceSearchType::class,$priceSearch);
        $form->handleRequest($request);
        $articles= [];
        if($form->isSubmitted() && $form->isValid()) {
            $minPrice = $priceSearch->getMinPrice();
            $maxPrice = $priceSearch->getMaxPrice();

            $articles= $this->getDoctrine()->
            getRepository(Article::class)->findByPriceRange($minPrice,$maxPrice);
        }
        return $this->render('article/articlesParPrix.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
 }
}

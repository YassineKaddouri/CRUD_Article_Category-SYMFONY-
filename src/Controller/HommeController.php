<?php

namespace App\Controller;

use App\Entity\Homme;
use App\Entity\Yassine;
use App\Form\YassineType;
use App\Repository\HommeRepository;
use App\Repository\YassineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HommeController extends AbstractController
{

   /* public function index(): Response
    {
        $articles =new Homme();
        $articles=$this->getDoctrine()->getRepository(Homme::class)->findAll();
        return $this->render('home/afficher.html.twig', [
            'articles' => $articles,
        ]);
    }
   */
    /**
     * @Route("/a", name="yassine")
     * @return Response
     */
    public function index()
    {
        $Listarticles=$this->getDoctrine()->getRepository(Yassine::class)->findAll();
       // $articles= $repo->findBy();
        return $this->render('home/afficher.html.twig', [
            'Listarticles'=> $Listarticles
        ]);
    }
    /**
     * @Route("/add", name="home")
     */
    public function new(Request $request,ManagerRegistry $managerRegistry){
        $home =new Yassine();
        $form= $this->createForm(YassineType::class,$home);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          // $file=$home->getImage();
          /*  $fileName =md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch(FileException $e){
            }*/

            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $home->setImage($newFilename);
            }
            $em = $managerRegistry->getManager();
           // $home->setImage($fileName);
            $em->persist($home);
            $em->flush();
            $this->addFlash(
                'success',
                "Bien Enregistrer"
            );
            return $this->redirectToRoute('article');


        }

        return $this->render('home/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * @Route("/a/{id}/edit",name="article_edit")
     * @return Response
     */
    public function update(Yassine $yassine,Request  $request,ManagerRegistry $managerRegistry){

        $form =$this->createForm(YassineType::class,$yassine);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $managerRegistry->getManager();
            $em->persist($yassine);
            $em->flush();
            $this->addFlash(
                'success',
                "Les modifications de L'article bien été enregestée!"
            );

            return $this->redirectToRoute('article');

        }
        return $this->render('home/edit.html.twig',[
            'form' => $form->createView()
        ]);


    }

}

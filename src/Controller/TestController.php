<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;





class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function foodHomePage(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function displayAll(ManagerRegistry $doctrine): Response
    {
        $foods = $doctrine->getRepository(Food::class)->findAll();
        /*   dd($foods); */
        return $this->render('food/index.html.twig', [
            "foods" => $foods
        ]);
    }

    /**
     * @Route("/details/{id}", name="details-food")
     */
    public function detailsAction(ManagerRegistry $doctrine, $id): Response
    {
        $food = $doctrine->getRepository(Food::class)->find($id); //or findBy(array("id"=>$id));
        /*   dd($trips); */
        return $this->render('food/details.html.twig', [
            "food" => $food
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete-food")
     */
    public function deleteAction(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $food = $doctrine->getRepository(Food::class)->find($id);
        if ($food->getPicture() != "picture.jpeg" && $food->getPicture() != NULL) {
            unlink("pictures/" . $food->getPicture());
        }
        $em->remove($food);
        $em->flush();
        /*   dd($trips); */
        return $this->redirectToRoute("index");
    }
    /**
     * @Route("/create", name="create-food")
     */
    public function createAction(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();
            //pictureUrl is the name given to the input field
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $food->setPicture($pictureFileName);
            } else {
                $food->setPicture("picture.jpeg");
            }
            //$form->getDAta()holds the submitted values but, the original `$task` variable has also been updated
            $food = $form->getData();
            //$food->setCreateDate(new \DateTime('now'));
            //perform some action, such as saving the task to the database

            $em = $doctrine->getManager();
            $em->persist($food);
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('food/create.html.twig', [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="edit-food")
     */
    public function editAction($id, Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $food = $doctrine->getRepository(Food::class)->find($id);
        $form = $this->createForm(FoodType::class, $food);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food = $form->getData();
            $pictureFile = $form->get('picture')->getData();
            //pictureUrl is the name given to the input field
            if ($pictureFile) {
                if ($food->getPicture()) {
                    unlink($this->getParameter('pictures_directory') . "/" . $food->getPicture());
                }
                $pictureFileName = $fileUploader->upload($pictureFile);
                $food->setPicture($pictureFileName);
            }
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated

            /*  $food->setCreateDate(new \DateTime('now')); */

            $em = $doctrine->getManager();

            $em->persist($food);
            $em->flush();

            return $this->redirectToRoute('index');
        }
        return $this->render('food/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }
}

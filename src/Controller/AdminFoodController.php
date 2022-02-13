<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Upload\UploadServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFoodController extends AbstractController
{
    private $manager;
    private $alert;
    public function __construct(EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        $this->alert = $alert;
        $this->manager = $manager;
    }

    #[Route('/admin/show_food', name: 'show_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function showFood(ManagerRegistry $doctrine)
    {
        $food = $doctrine->getRepository(Food::class)->findAll();

        return $this->render('admin/food/show_food.html.twig', [
            'foods' => $food
        ]);
    }

    #[Route('/admin/food/add_food', name: 'add_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function addFood(Request $request, UploadServiceInterface $uploadService)
    {
        $food = new Food();
        $form = $this->createform(FoodFormType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('img')->getData();

            if ($img) {

                $newFilename = $uploadService->upload($img, $this->getParameter('img_directory'));

                $food->setImg($newFilename);
                $food->setHide(0);
            }
            $this->manager->persist($food);
            $this->manager->flush();
            $this->alert->success("Le menu a bien été ajouté");

            return $this->redirectToRoute('show_food');
        }
        return $this->render('admin/food/add_food.html.twig', [
            'foodForm' => $form->createView()
        ]);
    }

    #[Route('/admin/food/edit_food/{id}', name: 'edit_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function editFood(Food $food, Request $request, UploadServiceInterface $uploadService): Response
    {

        $form = $this->createForm(FoodFormType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('img')->getData()) {
                $Newimg = $form->get('img')->getData();
                $Newimg = $uploadService->upload($Newimg, $this->getParameter('img_directory'));
                $food->setImg($Newimg);
            }
            $this->manager->persist($food);
            $this->manager->flush();
            $this->alert->success("Le menu a bien été mis à jour");
            return $this->redirectToRoute('show_food');
        }

        return $this->render('admin/food/edit_food.html.twig', [
            'foodEditForm' => $form->createView()
        ]);
    }

    #[Route('/admin/hide_food/{id}', name: 'hide_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function hideFood(Food $food)
    {
        $food->setHide(1);
        $this->manager->persist($food);
        $this->manager->flush();
        $this->alert->success('Votre plat a été retiré de la vente');
        return $this->redirectToRoute('show_food');
    }

    #[Route('/admin/unhide_food/{id}', name: 'unhide_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function unhideFood(Food $food)
    {
        $food->setHide(0);
        $this->manager->persist($food);
        $this->manager->flush();
        $this->alert->success('Votre plat a été remise en vente');
        return $this->redirectToRoute('show_food');
    }

    #[Route('/admin/food/{id}/delete/', name: 'delete_food')]
    /**
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function deleteFood(Food $food)
    {
        $this->manager->remove($food);
        $this->manager->flush();
        $this->alert->success("Le menu a été supprimé avec succès");
        return $this->redirectToRoute('show_menu');
    }
}

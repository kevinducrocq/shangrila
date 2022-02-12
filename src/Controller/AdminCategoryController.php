<?php

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController

{
    private $manager;
    private $alert;
    public function __construct(EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        $this->alert = $alert;
        $this->manager = $manager;
    }

    #[Route('/admin/category/show_category', name: 'show_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function showCategory(ManagerRegistry $doctrine)
    {
        $category = $doctrine->getRepository(Category::class)->findAll();
        return $this->render('admin/category/show_category.html.twig', [
            'categories' => $category
        ]);
    }

    #[Route('/admin/category/add_category', name: 'add_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function addCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setHide(0);
            $this->manager->persist($category);
            $this->manager->flush();
            $this->alert->success('Catégorie ajoutée');
            return $this->redirectToRoute('show_category');
        }
        return $this->render('admin/category/add_category.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/category/edit_category/{id}', name: 'edit_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function editCategory(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager > flush();
            $this->alert->success("La catégorie a bien été mise à jour");
            return $this->redirectToRoute('show_category');
        }
        return $this->render('admin/category/edit_category.html.twig', [
            'categoryEditForm' => $form->createView()
        ]);
    }

    #[Route('/admin/hide_category/{id}', name: 'hide_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function hideCategory(Category $category)
    {
        $category->setHide(1);
        $this->manager->persist($category);
        $this->manager->flush();
        $this->alert->success('Votre catégorie a été retirée de la vente');
        return $this->redirectToRoute('show_category');
    }

    #[Route('/admin/unhide_category/{id}', name: 'unhide_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function unhideCategory(Category $category)
    {
        $category->setHide(0);
        $this->manager->persist($category);
        $this->manager->flush();
        $this->alert->success('Votre catégorie a été remise en vente');
        return $this->redirectToRoute('show_category');
    }


    #[Route('/admin/category/{id}/delete', name: 'delete_category')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCategory(Category $category)
    {
        $this->manager->remove($category);
        $this->manager->flush();
        $this->alert->success("La catégorie a été supprimée avec succès");
        return $this->redirectToRoute('show_category');
    }
}

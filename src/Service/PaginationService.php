<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class PaginationService
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;

    private $manager;

    private $twig;
    private $route;

    private $templatePath;

    // 1- utiliser la pagination à partir de n'importe quelle entité / on devra préciser l'entité concernée

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function display()
    {
        // appelle le moteur twig et on précise quel template on veut utiliser

        $this->twig->display($this->templatePath, [
            // options nécessaires à l'affichage des données
            // variables: route / page / pages
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function setEntityClass($entityClass)
    {
        // ma donée entityClass = donnée qui va m'être envoyée

        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    // 2- Quelle est la limite ?

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    // 3- Sur quelle page je me trouve acutellement ?
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }

    // 4- On va chercher le nombre de pages au total pages

    public function getData()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("setEntityClass n'a pas été renseigné dans le controller correspondant");
        }

        // calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // demande au repository de trouver les éléments

        // aller chercher le bon repository
        $repo = $this->manager->getRepository($this->entityClass);

        // on construit la requête
        $data = $repo->findBy([], [], $this->limit, $offset);

        return $data;
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }
}

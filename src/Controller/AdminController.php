<?php

namespace App\Controller;

use id;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }


    #[Route('/admin/article/update/{id}', name:"admin_article_update")]
    #[Route('/admin/article/new', name:"admin_article_new")]
    public function formArticle(Request $request, EntityManagerInterface $manager, ArticleRepository $repo, $id = null)
    {
        if($id == null)
        {
            $article = new Article;
        }else{
            $article = $repo->find($id);
        }


        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime);
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('app_admin');

        }

        return $this->render('admin/formArticle.html.twig',[
            'form' => $form,
            'editMode' => $article->getId()!=null
        ]);
    }

    #[Route("/admin/article/gestion", name:"admin_article_gestion")]
    public function gestionArticle(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render('admin/gestionArticle.html.twig' , [
            'articles' => $articles
        ]);
    }

    #[Route('/admin/article/delete/{id}', name: 'admin_article_delete')]
    public function deleteArticle(Article $article, EntityManagerInterface $manager)
    {
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('admin_article_gestion');
    }
// ! Categorie CRUD
    #[Route("/admin/categorie/gestion", name:"admin_categorie_gestion")]
    public function gestionCategorie(CategoryRepository $repo)
    {
        $categories = $repo->findAll();
        return $this->render('admin/gestionCategory.html.twig' , [
            'categories' => $categories
        ]);
    }

    #[Route("/categorie/new", name:"admin_categorie_new")]
    public function formCategorie(Request $request, EntityManagerInterface $manager)
    {
        $categorie = new Category;

        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($categorie);
            $manager->flush();
            return $this->redirectToRoute('admin_categorie_gestion');
        }

        return $this->render('admin/formCategory.html.twig', [
            'form' => $form,

        ]);

    }

}
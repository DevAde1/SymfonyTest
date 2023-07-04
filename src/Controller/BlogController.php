<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig');
    }

    #[Route('/articles', name: 'articles')]
    public function articles(ArticleRepository $repo)
    {
        $articles =$repo->findAll();
        return $this->render('blog/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/show/{id}', name: 'article_show')]
    public function show(Article $article = null)
    {

        if($article)
        {
            return $this->render('blog/show.html.twig', [
                'article' => $article
            ]);

        } else{
            return $this->redirectToRoute('articles');
        }
       
    }


}

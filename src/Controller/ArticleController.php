<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTimeImmutable as GlobalDateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Monolog\DateTimeImmutable;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name:'app_article_index', methods:['GET'])]
function index(ArticleRepository $articleRepository): Response
    {
    return $this->render('article/index.html.twig', [
        'articles' => $articleRepository->findAll(),
    ]);
}

#[Route('/new', name:'app_article_new', methods:['GET', 'POST'])]
function new (Request $request, ArticleRepository $articleRepository, string $uploadDir, EntityManagerInterface $em): Response {
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /* $articleRepository->add($article); */
        $article->setPublishedAt(new GlobalDateTimeImmutable ());
        $article->setImage(sprintf('%s.%s', Uuid::v4(), $article->getImageFile()->getClientOriginalExtension()));
        $article->getImageFile()->move($uploadDir, $article->getImage());
        
        $em->persist($article);
        $em->flush();
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('article/new.html.twig', [
        'article' => $article,
        'form' => $form,
    ]);
}

#[Route('/{id}', name:'app_article_show', methods:['GET'])]
function show(Article $article): Response
    {
    return $this->render('article/show.html.twig', [
        'article' => $article,
    ]);
}

#[Route('/{id}/edit', name:'app_article_edit', methods:['GET', 'POST'])]
function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $articleRepository->add($article);
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('article/edit.html.twig', [
        'article' => $article,
        'form' => $form,
    ]);
}

#[Route('/{id}', name:'app_article_delete', methods:['POST'])]
function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
    if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
        $articleRepository->remove($article);
    }

    return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
}
}

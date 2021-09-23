<?php

namespace App\Controller\Blog;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Form\SearchType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    protected $repository;
    protected $tags;
    protected $paginator;

    public function __construct(PostRepository $repository, TagRepository $tags, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->tags = $tags;
        $this->paginator = $paginator;
    }

    // return tag
    public function getTag(Request $request): ?Tag
    {
        $tag = null;
        if($request->query->has('tag')) {
            $tag = $this->tags->findOneBy(['name' => $request->query->get('tag')]);
        }

        return $tag;
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        $posts = new Post();
        $data = new SearchData();

        // search
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $posts = $this->repository->findSearch($data);
        }

        return $this->render('page/blog/search.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
            'data' => $data,
        ]);
    }

    // search
    public function searchForm(Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
        }

        return $this->render('page/blog/fragment/_form.html.twig', [
            'form' => $form->createView(),
            'data' => $data
        ]);

    }
}
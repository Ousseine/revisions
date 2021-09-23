<?php

namespace App\Controller\Blog;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="blog_")
 */
class PostController extends AbstractController
{
    protected $postRep;
    protected $tagRepo;
    protected $paginator;

    public function __construct(PostRepository $postRep,TagRepository $tagRepo, PaginatorInterface $paginator)
    {
        $this->postRep = $postRep;
        $this->tagRepo = $tagRepo;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $posts = $this->paginator->paginate(
            $this->postRep->findAllByDesc(),
            $request->query->getInt('page', 1),
            12
        );

        $tags = $this->tagRepo->allTag();

        return $this->render('page/blog/index.html.twig', [
            'posts' => $posts,
            'tags' => $tags
        ]);
    }

    /**
     * @Route("post/{slug}", name="post")
     */
    public function postShow(Post $post): Response
    {
        return $this->render('page/blog/post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/category/{name}", name="category")
     */
    public function postCategory(Category $category, Request $request): Response
    {
        $posts = $this->paginator->paginate(
            $category->getPosts(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('page/blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/tag/{name}", name="tag")
     */
    public function postTag(Tag $tag, Request $request): Response
    {
        $posts = $this->paginator->paginate(
            $tag->getPosts(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('page/blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}

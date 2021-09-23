<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    protected $twig;
    protected $tags;
    protected $cache;
    protected $categories;

    public function __construct(Environment $twig, TagRepository $tags, CategoryRepository $categories)
    {
        $this->twig = $twig;
        $this->tags = $tags;
        $this->categories = $categories;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('filter', [$this, 'renderFilter'], ['is_safe' => ['html']])
        ];
    }

    public function renderFilter(): string
    {
        return $this->twig->render('include/filter.html.twig', [
            'tags' => $this->tags->allTag(),
            'categories' => $this->categories->allCategory()
        ]);
    }
}
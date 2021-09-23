<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // user
        $users = [];
        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $manager->persist($user);
            $users[] = $user;
        }

        // comment
        $comments = [];
        for($i = 0; $i < 5; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->paragraph);
            $comment->setPublishedAt(new \DateTimeImmutable());
            $comment->setUpdatedAt(new \DateTimeImmutable());
            $comment->setAuthor($users[$faker->numberBetween(0, 9)]);
            $comment->setIsValid(true);
            $manager->persist($comment);
            $comments[] = $comment;
        }

        // tag
        $tags = [];
        for ($i = 0; $i < 20; $i++) {
            $tag = new Tag();
            $tag->setName($faker->name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // category
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->name);
            $manager->persist($category);
            $categories[] = $category;
        }
        
        // post
        for($i = 0; $i < 100; $i++) {
            $post = new Post;
            $post->setTitle($faker->sentence);
            $post->setSlug(strtolower($faker->slug));
            $post->setSummary($faker->sentence(5, true));
            $post->setContent($faker->realText(1000));
            $post->setImageName($faker->imageUrl(640, 480));
            $post->setPublishedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable());
            $post->addTag($tags[$faker->numberBetween(6, 10)]);
            $post->addCategory($categories[$faker->numberBetween(4, 8)]);
            $post->setAuthor($users[$faker->numberBetween(0, 9)]);
            $post->addComment($comments[$faker->numberBetween(2, 4)]);
            $manager->persist($post);
        }

        $manager->flush();
    }
}

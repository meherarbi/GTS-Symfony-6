<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private string $uploadDir)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        /** @var array<array-key, Category> $categories */
        $category = $manager->getRepository(Category::class)->findAll();
        /** @var array<array-key, Tags> $tags */
        $tags = $manager->getRepository(Tags::class)->findAll();
        foreach ($category as $categories) {
            for ($i = 1; $i <= 10; ++$i) {
                shuffle($tags);
                $Article = new Article();
                $Article->setCategory($categories);
                $Article->setPublishedAt(new \DateTimeImmutable());
                $Article->setTitle($faker->words(3, true));
                $Article->setContent($faker->paragraphs(2, true));
                $Article->setImage($faker->image($this->uploadDir, 640, 480, null, false));
                foreach (array_slice($tags, 0, 2) as $tag) {
                    $Article->getTags()->add($tag);
                }
                
                $manager->persist($Article);
            }

        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            TagsFixtures::class,

        ];
    }
}

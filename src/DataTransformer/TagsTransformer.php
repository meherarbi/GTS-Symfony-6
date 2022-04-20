<?php


namespace App\DataTransformer;

use App\Entity\Tags;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

final class TagsTransformer implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    

    /**
     * @param Collection<int, Tags> $value
     * @return string
     */
    public function transform(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        return implode(',', $value->map(static fn (Tags $tag): string => $tag->getName())->toArray());
    }

   /**
     * @param string $value
     * @return Collection<int, Tag>
     */
    public function reverseTransform(mixed $value): Collection
    {
        $tags = u($value)->split(',');

        array_walk($tags, static fn (string &$tagName): string => u($tagName)->trim()->toString());

        $tagsCollection = new ArrayCollection();

        $tagsRepository = $this->entityManager->getRepository(Tags::class);

        foreach ($tags as $tagName) {
            if ($tagName === '') {
                continue;
            }

            $tag = $tagsRepository->findOneBy(['name' => $tagName]);

            if ($tag === null) {
                $tag = new Tags();
                $tag->setName($tagName);
                $this->entityManager->persist($tag);
            }

            $tagsCollection->add($tag);
        }

        return $tagsCollection;
    }
}
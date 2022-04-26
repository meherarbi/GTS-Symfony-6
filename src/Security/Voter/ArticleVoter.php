<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;

final class ArticleVoter implements CacheableVoterInterface
{
    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === 'edit';
    }

    public function supportsType(string $subjectType): bool
    {
        return $subjectType === Article::class;
    }

    /**
     * @param Article $subject
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes)
    {
        /** @var User|null $user */
        $user = $token->getUser();

        return $user != null && $subject->getUser() === $user;
    }
}

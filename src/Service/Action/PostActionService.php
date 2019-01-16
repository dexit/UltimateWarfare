<?php

declare(strict_types=1);

namespace FrankProjects\UltimateWarfare\Service\Action;

use FrankProjects\UltimateWarfare\Entity\Post;
use FrankProjects\UltimateWarfare\Entity\Topic;
use FrankProjects\UltimateWarfare\Entity\User;
use FrankProjects\UltimateWarfare\Repository\PostRepository;
use FrankProjects\UltimateWarfare\Util\ForumHelper;
use RuntimeException;

final class PostActionService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var ForumHelper
     */
    private $forumHelper;

    /**
     * PostActionService service
     *
     * @param PostRepository $postRepository
     * @param ForumHelper $forumHelper
     */
    public function __construct(
        PostRepository $postRepository,
        ForumHelper $forumHelper
    ) {
        $this->postRepository = $postRepository;
        $this->forumHelper = $forumHelper;
    }

    /**
     * @param Post $post
     * @param Topic $topic
     * @param User $user
     * @param string $ipAddress
     */
    public function create(Post $post, Topic $topic, User $user, string $ipAddress): void
    {
        $this->forumHelper->ensureNotBanned($user);
        $this->forumHelper->ensureNoMassPost($user);

        try {
            $dateTime = new \DateTime();
        } catch (\Exception $e) {
            throw new RunTimeException("DateTime exception: {$e->getMessage()}");
        }

        $post->setTopic($topic);
        $post->setPosterIp($ipAddress);
        $post->setCreateDateTime($dateTime);
        $post->setUser($user);

        $this->postRepository->save($post);
    }

    /**
     * @param Post $post
     * @param User $user
     * @throws \Exception
     */
    public function edit(Post $post, User $user): void
    {
        $this->forumHelper->ensureNotBanned($user);
        $this->forumHelper->ensureNoMassPost($user);

        if ($user->getId() != $post->getUser()->getId() && !$user->hasRole('ROLE_ADMIN')) {
            throw new RunTimeException('Not enough permissions!');
        }

        $post->setEditUser($user);
        $this->postRepository->save($post);
    }

    /**
     * @param Post $post
     * @param User|null $user
     */
    public function remove(Post $post, ?User $user): void
    {
        $this->forumHelper->ensureNotBanned($user);

        if ($user === null) {
            throw new RunTimeException('You are not logged in!');
        }

        if ($user->getId() != $post->getUser()->getId() && !$user->hasRole('ROLE_ADMIN')) {
            throw new RunTimeException('Not enough permissions!');
        }

        $this->postRepository->remove($post);
    }
}

<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsMessageHandler]
readonly class CommentMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SpamChecker            $spamChecker,
        private CommentRepository      $commentRepository,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(CommentMessage $message): void
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) {
            return;
        }

        $commentSpamScore = $this->spamChecker->getSpamScore($comment, $message->getContext());

        if (2 === $commentSpamScore) {
            $comment->setState('spam');
        } elseif (1 === $commentSpamScore) {
            $comment->setState('maybeSpam');
        } else {
            $comment->setState('published');
        }

        $this->entityManager->flush();
    }
}
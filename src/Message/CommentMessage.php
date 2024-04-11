<?php

declare(strict_types=1);

namespace App\Message;

readonly class CommentMessage
{
    public function __construct(
        private int   $id,
        private array $context = [],
    )
    {
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get context
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }


}
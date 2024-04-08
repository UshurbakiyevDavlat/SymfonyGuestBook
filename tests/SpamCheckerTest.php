<?php

namespace App\Tests;

use App\Entity\Comment;
use App\SpamChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpamCheckerTest extends TestCase
{
    /**
     * @dataProvider provideComments
     * @throws TransportExceptionInterface
     */
    public function testSpamScoreWithInvalidRequest(
        int               $expectedScore,
        ResponseInterface $response,
        Comment           $comment,
        array             $context,
    ): void
    {
        $client = new MockHttpClient([$response]);
        $checker = new SpamChecker($client, 'abcde');

        $score = $checker->getSpamScore($comment, $context);
        $this->assertSame($expectedScore, $score);
    }

    public static function provideComments(): iterable
    {
        $comment = new Comment();
        $comment->setAuthor('TestUser');
        $comment->setEmail('test@test.com');
        $comment->setText('testText');

        $comment->setCreatedAtValue();
        $context = [];

        $response = new MockResponse(
            '', [
                'response_headers' => ['x-akismet-pro-tip: discard']
            ]
        );
        yield 'blatant_spam' => [2, $response, $comment, $context];

        $response = new MockResponse('true');
        yield 'spam' => [1, $response, $comment, $context];

        $response = new MockResponse('false');
        yield 'ham' => [0, $response, $comment, $context];
    }
}

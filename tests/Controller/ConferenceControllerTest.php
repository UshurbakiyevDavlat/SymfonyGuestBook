<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/en/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/en/conference/amsterdam-2019');
        $client->submitForm('Submit', [
            'comment[author]' => 'Fabien',
            'comment[text]' => 'Some feedback for automation functional test',
            'comment[email]' => $email = 'me@automata.ed',
            'comment[photoFilename]' => dirname(__DIR__, 2) . '/public/images/under-construction.gif',
        ]);

        $this->assertResponseRedirects();

        $comment = self::getContainer()->get(CommentRepository::class)->findOneByEmail($email);
        $comment->setState('published');
        self::getContainer()->get(EntityManagerInterface::class)->flush();

        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 2 comments")');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/');

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');
        #$client->click($crawler->filter('h4 + p a')->link());

        $this->assertPageTitleContains('Amsterdam');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Amsterdam 2019');
        $this->assertSelectorExists('div:contains("There is one comment")');
    }

//    public function testMailerAssertions() TODO непонятно почему не работает
//    {
//        $client = static::createClient();
//        $client->request('GET', '/');
//        $this->assertEmailCount(1);
//
//        $event = $this->getMailerEvent(0);
//        $this->assertEmailIsQueued($event);
//
//        $email = $this->getMailerMessage(0);
//        $this->assertEmailHeaderSame($email, 'To', 'fabien@example.com');
//        $this->assertEmailTextBodyContains($email, 'Bar');
//        $this->assertEmailAttachmentCount($email, 0);
//    }
}
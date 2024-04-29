<?php

namespace App\Test\Controller;

use App\Entity\Responses;
use App\Repository\ResponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResponsesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ResponsesRepository $repository;
    private string $path = '/responses/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Responses::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Response index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'response[idsup]' => 'Testing',
            'response[emailsup]' => 'Testing',
            'response[reponse]' => 'Testing',
            'response[dater]' => 'Testing',
        ]);

        self::assertResponseRedirects('/responses/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Responses();
        $fixture->setIdsup('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setReponse('My Title');
        $fixture->setDater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Response');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Responses();
        $fixture->setIdsup('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setReponse('My Title');
        $fixture->setDater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'response[idsup]' => 'Something New',
            'response[emailsup]' => 'Something New',
            'response[reponse]' => 'Something New',
            'response[dater]' => 'Something New',
        ]);

        self::assertResponseRedirects('/responses/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getIdsup());
        self::assertSame('Something New', $fixture[0]->getEmailsup());
        self::assertSame('Something New', $fixture[0]->getReponse());
        self::assertSame('Something New', $fixture[0]->getDater());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Responses();
        $fixture->setIdsup('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setReponse('My Title');
        $fixture->setDater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/responses/');
    }
}

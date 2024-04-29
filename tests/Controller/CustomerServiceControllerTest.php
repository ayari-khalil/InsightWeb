<?php

namespace App\Test\Controller;

use App\Entity\CustomerService;
use App\Repository\CustomerServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerServiceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CustomerServiceRepository $repository;
    private string $path = '/customer/service/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(CustomerService::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('CustomerService index');

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
            'customer_service[fullname]' => 'Testing',
            'customer_service[emailsup]' => 'Testing',
            'customer_service[pnsup]' => 'Testing',
            'customer_service[issue]' => 'Testing',
            'customer_service[subject]' => 'Testing',
            'customer_service[message]' => 'Testing',
            'customer_service[stater]' => 'Testing',
        ]);

        self::assertResponseRedirects('/customer/service/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new CustomerService();
        $fixture->setFullname('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setPnsup('My Title');
        $fixture->setIssue('My Title');
        $fixture->setSubject('My Title');
        $fixture->setMessage('My Title');
        $fixture->setStater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('CustomerService');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new CustomerService();
        $fixture->setFullname('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setPnsup('My Title');
        $fixture->setIssue('My Title');
        $fixture->setSubject('My Title');
        $fixture->setMessage('My Title');
        $fixture->setStater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'customer_service[fullname]' => 'Something New',
            'customer_service[emailsup]' => 'Something New',
            'customer_service[pnsup]' => 'Something New',
            'customer_service[issue]' => 'Something New',
            'customer_service[subject]' => 'Something New',
            'customer_service[message]' => 'Something New',
            'customer_service[stater]' => 'Something New',
        ]);

        self::assertResponseRedirects('/customer/service/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFullname());
        self::assertSame('Something New', $fixture[0]->getEmailsup());
        self::assertSame('Something New', $fixture[0]->getPnsup());
        self::assertSame('Something New', $fixture[0]->getIssue());
        self::assertSame('Something New', $fixture[0]->getSubject());
        self::assertSame('Something New', $fixture[0]->getMessage());
        self::assertSame('Something New', $fixture[0]->getStater());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new CustomerService();
        $fixture->setFullname('My Title');
        $fixture->setEmailsup('My Title');
        $fixture->setPnsup('My Title');
        $fixture->setIssue('My Title');
        $fixture->setSubject('My Title');
        $fixture->setMessage('My Title');
        $fixture->setStater('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/customer/service/');
    }
}

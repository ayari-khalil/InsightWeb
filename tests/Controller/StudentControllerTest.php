<?php

namespace App\Test\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/student/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Student::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Student index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'student[email]' => 'Testing',
            'student[password]' => 'Testing',
            'student[firstName]' => 'Testing',
            'student[lastName]' => 'Testing',
            'student[birthDate]' => 'Testing',
            'student[phoneNumber]' => 'Testing',
            'student[cin]' => 'Testing',
            'student[role]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Student();
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setBirthDate('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setCin('My Title');
        $fixture->setRole('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Student');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Student();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setBirthDate('Value');
        $fixture->setPhoneNumber('Value');
        $fixture->setCin('Value');
        $fixture->setRole('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'student[email]' => 'Something New',
            'student[password]' => 'Something New',
            'student[firstName]' => 'Something New',
            'student[lastName]' => 'Something New',
            'student[birthDate]' => 'Something New',
            'student[phoneNumber]' => 'Something New',
            'student[cin]' => 'Something New',
            'student[role]' => 'Something New',
        ]);

        self::assertResponseRedirects('/student/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getBirthDate());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getRole());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Student();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setBirthDate('Value');
        $fixture->setPhoneNumber('Value');
        $fixture->setCin('Value');
        $fixture->setRole('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/student/');
        self::assertSame(0, $this->repository->count([]));
    }
}

<?php

namespace Olenaza\BlogBundle\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Olenaza\BlogBundle\Entity\User;
use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Entity\Comment;
use Symfony\Component\PropertyAccess\PropertyAccess;
use GuzzleHttp\Event\BeforeEvent;

class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    private $responseAsserter;

    /**
     * @var Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        $baseUrl = getenv('TEST_BASE_URL');
        self::$staticClient = new Client([
            'base_url' => $baseUrl,
            'defaults' => [
                'exceptions' => false,
            ],
        ]);

        self::bootKernel();

        self::$staticClient->getEmitter()
            ->on('before', function (BeforeEvent $event) {
                $path = $event->getRequest()->getPath();
                if (strpos($path, '/api') === 0) {
                    $event->getRequest()->setPath('/app_test.php'.$path);
                }
            });
    }

    protected function setUp()
    {
        $this->client = self::$staticClient;

        $this->purgeDatabase();
    }

    protected function tearDown()
    {
    }

    /**
     * @param $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return self::$kernel->getContainer()->get($id);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

    /**
     * @param array $userData
     *
     * @return User
     */
    protected function createUser(array $userData)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $user = new User();

        foreach ($userData as $key => $value) {
            $accessor->setValue($user, $key, $value);
        }

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @param array $postData
     *
     * @return Post
     */
    protected function createPost(array $postData)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $post = new Post();

        foreach ($postData as $key => $value) {
            $accessor->setValue($post, $key, $value);
        }

        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();

        return $post;
    }

    /**
     * @param array $commentData
     *
     * @return Comment
     */
    protected function createComment(array $commentData)
    {
        $post = $this->getEntityManager()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy(['title' => 'Post0 Title']);
        $user = $this->getEntityManager()
            ->getRepository('OlenazaBlogBundle:User')
            ->findOneBy(['username' => 'Name0']);

        $accessor = PropertyAccess::createPropertyAccessor();

        $comment = new Comment($post, $user);

        foreach ($commentData as $key => $value) {
            $accessor->setValue($comment, $key, $value);
        }

        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();

        return $comment;
    }

    /**
     * @return int
     */
    protected function getLimitPostsPerPage()
    {
        return self::$kernel->getContainer()->getParameter('posts_per_page');
    }

    /**
     * @return object
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.default_entity_manager');
    }

    /**
     * @return ResponseAsserter
     */
    protected function asserter()
    {
        if ($this->responseAsserter === null) {
            $this->responseAsserter = new ResponseAsserter();
        }

        return $this->responseAsserter;
    }
}

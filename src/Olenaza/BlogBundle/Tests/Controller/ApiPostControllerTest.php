<?php

namespace Olenaza\BlogBundle\Tests\Controller;

use Olenaza\BlogBundle\Tests\ApiTestCase;

class ApiPostControllerTest extends ApiTestCase
{
    protected function setup()
    {
        parent::setUp();

        $this->createUser([
            'username' => 'Name0',
            'email' => 'name0@foo.com',
            'password' => 'foo',
        ]);

        $this->createPost([
            'title' => 'Post0 Title',
            'subtitle' => 'Post0 Subtitle',
            'beginning' => 'Post0 Beginning',
            'text' => 'Post0 Text',
            'coverImage' => 'https://www.post0_image.net',
            'forPublication' => true,
            'published' => true,
            'publishedOn' => new \DateTime(),
        ]);
    }

    public function testGETPostsCollection()
    {
        $this->createPost([
            'title' => 'Post1 Title',
            'subtitle' => 'Post1 Subtitle',
            'beginning' => 'Post1 Beginning',
            'text' => 'Post1 Text',
            'coverImage' => 'https://www.post1_image.net',
            'forPublication' => true,
            'published' => true,
            'publishedOn' => new \DateTime(),
        ]);

        $response = $this->client->get('api/posts');

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyIsArray($response, 'posts');

        $this->asserter()->assertResponsePropertyCount($response, 'posts', 2);

        $this->asserter()->assertResponsePropertiesExist($response, [
            'posts[1].id',
            'posts[1].title',
            'posts[1].beginning',
            'posts[1].coverImage',
            'posts[1].publishedOn',
            'posts[1].slug',
            'posts[1].tags',
            'posts[1].categories',
            'posts[1].comments',
            'posts[1].likes',
        ]);

        $this->asserter()->assertResponsePropertyEquals($response, 'posts[1].title', 'Post1 Title');
    }

    public function testGETPost()
    {
        $post = $this->createPost([
            'title' => 'Post2 Title',
            'subtitle' => 'Post2 Subtitle',
            'beginning' => 'Post2 Beginning',
            'text' => 'Post2 Text',
            'coverImage' => 'https://www.post2_image.net',
            'forPublication' => true,
            'published' => true,
            'publishedOn' => new \DateTime(),
        ]);

        $postSlug = $post->getSlug();

        $response = $this->client->get('api/posts/'.$postSlug);

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertiesExist($response, [
            'title',
            'subtitle',
            'text',
            'publishedOn',
            'slug',
            'tags',
            'categories',
            'comments',
            'likes',
        ]);

        $this->asserter()->assertResponsePropertyEquals($response, 'text', 'Post2 Text');
    }

    public function testCommentPOST()
    {
        $data = [
            'text' => 'ApiComment',
        ];

        $response = $this->client->post('api/posts/post0-title/comments', [
            'body' => json_encode($data),
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertTrue($response->hasHeader('Location'));

        $finishedData = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('text', $finishedData);

        $this->assertEquals('ApiComment', $finishedData['text']);

        $location = 'api/posts/post0-title/comments/'.$finishedData['id'];

        $this->assertStringEndsWith($location, $response->getHeader('Location'));
    }

    public function testGETComment()
    {
        $comment = $this->createComment([
            'text' => 'UnitTester',
        ]);

        $postSlug = $comment->getPost()->getSlug();

        $commentId = $comment->getId();

        $response = $this->client->get('api/posts/'.$postSlug.'/comments/'.$commentId);

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertiesExist($response, [
            'user',
            'publishedAt',
            'updatedAt',
            'text',
            'id',
        ]);

        $this->asserter()->assertResponsePropertyEquals($response, 'text', 'UnitTester');
    }

    public function testGETCommentsCollection()
    {
        $comment1 = $this->createComment([
            'text' => 'UnitTester0',
        ]);
        $comment2 = $this->createComment([
            'text' => 'UnitTester1',
        ]);

        $postSlug = $comment1->getPost()->getSlug();

        $response = $this->client->get('api/posts/'.$postSlug.'/comments');

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyIsArray($response, 'comments');

        $this->asserter()->assertResponsePropertyCount($response, 'comments', 2);

        $this->asserter()->assertResponsePropertyEquals($response, 'comments[1].text', 'UnitTester1');
    }

    public function testPUTComment()
    {
        $comment = $this->createComment([
            'text' => 'ApiComment',
        ]);

        $data = [
            'text' => 'ApiCommentUpdated',
        ];

        $postSlug = $comment->getPost()->getSlug();

        $commentId = $comment->getId();

        $response = $this->client->put('api/posts/'.$postSlug.'/comments/'.$commentId, [
            'body' => json_encode($data),
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyEquals($response, 'text', 'ApiCommentUpdated');
    }

    public function testDELETEComment()
    {
        $comment = $this->createComment([
            'text' => 'UnitTester',
        ]);

        $postSlug = $comment->getPost()->getSlug();

        $commentId = $comment->getId();

        $response = $this->client->delete('api/posts/'.$postSlug.'/comments/'.$commentId);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testGETPostsCollectionPaginated()
    {
        for ($i = 1; $i < 25; ++$i) {
            $this->createPost([
                'title' => "Post$i Title",
                'subtitle' => "Post$i Subtitle",
                'beginning' => "Post$i Beginning",
                'text' => "Post$i Text",
                'coverImage' => "https://www.post($i)_image.net",
                'forPublication' => true,
                'published' => true,
                'publishedOn' => new \DateTime(),
            ]);
        }

        $response = $this->client->get('api/posts');

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'posts[4].title',
            'Post4 Title'
        );

        $limit = $this->getLimitPostsPerPage();

        $this->asserter()->assertResponsePropertyEquals($response, 'count', $limit);

        $this->asserter()->assertResponsePropertyEquals($response, 'total', 25);

        $this->asserter()->assertResponsePropertyExists($response, '_links.next');

        $nextLink = $this->asserter()->readResponseProperty($response, '_links.next');
        $response = $this->client->get($nextLink);

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'posts[4].title',
            'Post9 Title'
        );
        $this->asserter()->assertResponsePropertyEquals($response, 'count', $limit);

        $lastLink = $this->asserter()->readResponseProperty($response, '_links.last');
        $response = $this->client->get($lastLink);

        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'posts[4].title',
            'Post25 Title'
        );
        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'posts[5].title');

        $this->asserter()->assertResponsePropertyEquals($response, 'count', $limit);
    }
}

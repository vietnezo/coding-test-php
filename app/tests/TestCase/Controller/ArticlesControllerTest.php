<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\ArticlesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArticlesController Test Case
 *
 * @uses \App\Controller\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Articles',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\ArticlesController::index()
     */
    public function testIndex(): void
    {
        $this->get('/articles.json');
        $this->assertResponseOk();
        // Add your assertions here
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\ArticlesController::view()
     */
    public function testView(): void
    {
        $this->get('/articles/view/1');
        $this->assertResponseOk();
        // Add your assertions here
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\ArticlesController::add()
     */
    public function testAdd(): void
    {
        $data = [
            'title' => 'Test Article',
            'body' => 'This is a test article.',
        ];
        $this->post('/articles/add', $data);
        $this->assertResponseSuccess();
        // Add your assertions here
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\ArticlesController::edit()
     */
    public function testEdit(): void
    {
        $data = [
            'title' => 'Updated Article',
            'body' => 'This is an updated article.',
        ];
        $this->put('/articles/edit/1', $data);
        $this->assertResponseSuccess();
        // Add your assertions here
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\ArticlesController::delete()
     */
    public function testDelete(): void
    {
        $this->delete('/articles/delete/1');
        $this->assertResponseSuccess();
        // Add your assertions here
    }
}

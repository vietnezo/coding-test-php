<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Articles Controller
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function viewClasses(): array
    {
        return [JsonView::class, XmlView::class];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles, [
                        'order' => ['created_at' => 'DESC']
                    ])
                    ->toArray();
        $articleIds = array_map(fn($article) => $article->id, $articles);
        // Get the UserArticleLikes count for each article
        $likesCounts = $this->Articles->UserArticleLikes->find()
            ->select(['article_id', 'likes_count' => 'COUNT(*)'])
            ->where(['article_id IN' => $articleIds])
            ->group('article_id')
            ->indexBy('article_id')
            ->toArray();
        foreach ($articles as $article) {
            $article->likes_count = $likesCounts[$article->id]->likes_count ?? 0;
        }

        $this->set(compact('articles'));
        $this->viewBuilder()->setOption('serialize', ['articles']);
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id);
        // Get the like count for the article
        $likesCount = $this->Articles->UserArticleLikes->find()
            ->select(['likes_count' => 'COUNT(*)'])
            ->where(['article_id' => $article->id])
            ->first();
        $article->likes_count = $likesCount->likes_count ?? 0;

        $this->set(compact('article'));
        $this->viewBuilder()->setOption('serialize', 'article');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();

        $this->request->allowMethod('post');
        $article = $this->Articles->patchEntity($article, $this->request->getData());
        if ($article->getErrors()) {
            $result = [
                'message' => __('The article could not be saved. Please, try again.'),
                'errors' => $article->getErrors(),
            ];
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', 'result');
            return;
        }

        $result = [];
        try {
            $article->user_id = $this->Authentication->getIdentityData('id');
            $this->Articles->save($article);
            $result = [
                'message' => __('The article has been saved.'),
                'article' => $article,
            ];
        } catch (\Exception $e) {
            $result = [
                'message' => __('The article could not be saved. Please, try again.'),
                'errors' => $e->getMessage(),
            ];
        }
        $this->set(compact('result'));
        $this->viewBuilder()->setOption('serialize', 'result');
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user_id = $this->Authentication->getIdentityData('id');
            if ($article->user_id !== $user_id) {
                $result = [
                    'status' => 'error',
                    'message' => __('You are not authorized to edit this article.'),
                ];
                $this->setResponse($this->getResponse()->withStatus(401));
                $this->set(compact('result'));
                $this->viewBuilder()->setOption('serialize', 'result');
                return;
            }

            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($article->getErrors()) {
                $result = [
                    'status' => 'error',
                    'message' => __('The article could not be saved. Please, try again.'),
                    'errors' => $article->getErrors(),
                ];
                $this->set(compact('result'));
                $this->viewBuilder()->setOption('serialize', 'result');
                return;
            }

            $result = [];
            try {
                $this->Articles->save($article);
                $result = [
                    'status' => 'success',
                    'message' => __('The article has been saved.'),
                    'article' => $article,
                ];
            } catch (\Exception $e) {
                $result = [
                    'status' => 'error',
                    'message' => __('The article could not be saved. Please, try again.'),
                    'errors' => $e->getMessage(),
                ];
            }
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', 'result');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->get($id);
        $user_id = $this->Authentication->getIdentityData('id');
        if ($article->user_id !== $user_id) {
            $result = [
                'status' => 'error',
                'message' => __('You are not authorized to delete this article.'),
            ];
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', 'result');
            return;
        }

        $result = [];
        try {
            $this->Articles->delete($article);
            $result = [
                'status' => 'success',
                'message' => __('The article has been deleted.'),
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => 'error',
                'message' => __('The article could not be deleted. Please, try again.'),
                'errors' => $e->getMessage(),
            ];
        }

        $this->set(compact('result'));
        $this->viewBuilder()->setOption('serialize', 'result');
    }
}

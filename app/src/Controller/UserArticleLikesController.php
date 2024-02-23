<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * UserArticleLikes Controller
 *
 * @property \App\Model\Table\UserArticleLikesTable $UserArticleLikes
 * @method \App\Model\Entity\UserArticleLike[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserArticleLikesController extends AppController
{
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod('post');
        $requestData = $this->request->getData();
        $articleId = $requestData['article_id'];
        $userId = $this->Authentication->getIdentityData('id');

        $existingLike = $this->UserArticleLikes->find()
            ->where(['article_id' => $articleId, 'user_id' => $userId])
            ->first();
        if ($existingLike) {
            $result = [
                'status' => 'error',
                'message' => 'The like already exists.',
            ];
            $this->setResponse($this->getResponse()->withStatus(400));
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', ['result']);
            return;
        }

        $userLike = $this->UserArticleLikes->newEmptyEntity();
        $userLike = $this->UserArticleLikes->patchEntity($userLike, [
            'article_id' => $articleId,
            'user_id' => $userId,
        ]);

        if ($this->UserArticleLikes->save($userLike)) {
            $result = [
                'status' => 'success',
                'message' => 'The like has been saved.',
            ];
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', ['result']);
        } else {
            $result = [
                'status' => 'error',
                'message' => 'The like could not be saved. Please, try again.',
                'errors' => $userLike->getErrors(),
            ];
            $this->setResponse($this->getResponse()->withStatus(400));
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', ['result']);
        }
    }
}

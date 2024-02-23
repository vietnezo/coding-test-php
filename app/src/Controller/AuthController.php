<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Utility\Security;
use Cake\View\JsonView;
use Firebase\JWT\JWT;
/**
 * Auth Controller
 *
 * @method \App\Model\Entity\Auth[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuthController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    {
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $privateKey = file_get_contents(CONFIG . '/credentials/jwt.key');  //private key
            $user = $result->getData();
            $payload = [
                'iss' => 'miles',
                'sub' => $user->id,
                'exp' => strtotime('+1 week'), // given 1 week token expiry
            ];

            $json = [
                'status' => 'success',
                'token' => JWT::encode($payload, $privateKey, 'RS256'),
            ];
        } else {
            $this->response = $this->response->withStatus(401);
            $json = [
                'status' => 'error',
                'message' => 'Invalid username or password',
            ];
        }
        $this->set(compact('json'));
        $this->viewBuilder()->setOption('serialize', 'json');
    }
}

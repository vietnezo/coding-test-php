<?php
declare(strict_types=1);

use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $passwordHasher = new DefaultPasswordHasher();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'email' => 'user' . ($i + 1) . '@example.com',
                'password' => $passwordHasher->hash('password'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}

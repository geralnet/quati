<?php
declare(strict_types = 1);

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@quati.test',
            'password' => bcrypt('admin'),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id'       => 1,
            `user_kode`     => 'lontong',
            'name'          => 'lintang27',
            'password'      => '$2y$10$JW.dsYoc.54EK86n6Y6lGebJvzqcof2vuK.RoqlMMHBPWS6Q05CGO',
            's_role_id'     => 3,
            'created_by'    => 1,
            'created_date'  => '2021-04-23 16:53:07',
            'updated_by'    => null,
            'updated_date'  => '2021-04-23 16:53:07',
            'revised'       => 0,
            'user_aktif'    => 'y',
            'disabled_by'   => null,
            'disabled_date' => null,
            'disabled_alasan' => null
        ], [
            'user_id'       => 2,
            `user_kode`     => 'admin',
            'name'          => 'admin27',
            'password'      => '$2y$10$i9ZnzVfS5ZQL6vgWJzjWxehNFKCQoThRGnzskFfYQbS.OeLr.3PTC',
            's_role_id'     => 3,
            'created_by'    => 1,
            'created_date'  => '2021-04-23 16:53:07',
            'updated_by'    => null,
            'updated_date'  => '2021-04-23 16:53:07',
            'revised'       => 0,
            'user_aktif'    => 'y',
            'disabled_by'   => null,
            'disabled_date' => null,
            'disabled_alasan' => null
        ]);
    }
}

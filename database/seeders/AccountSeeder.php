<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            [
                'type' => 'Patient',
                'user_name' => 'patient123',
                'password' => Hash::make('secret123'),
            ],
            [
                'type' => 'Related',
                'user_name' => 'relative456',
                'password' => Hash::make('secret123'),
            ],
            [
                'type' => 'Patient',
                'user_name' => 'john.doe',
                'password' => Hash::make('secret123'),
            ],
        ];

        DB::table('accounts')->insert($accounts);
    }
}

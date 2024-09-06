<?php

namespace Database\Seeders;

use App\Models\ClientBond;
use App\Models\Radius\Radgroupcheck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ClientBondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servidores = Radgroupcheck::firstOrCreate(
            ['groupname' => 'servidores'],
            [
                'groupname' => 'servidores',
                'attribute' => 'Simultaneous-Use',
                'op' => ':=',
                'value' => 4
            ]
        );

        $colaboradores = Radgroupcheck::firstOrCreate(
            ['groupname' => 'colaboradores'],
            [
                'groupname' => 'colaboradores',
                'attribute' => 'Simultaneous-Use',
                'op' => ':=',
                'value' => 2
            ]
        );

        $discentes = Radgroupcheck::firstOrCreate(
            ['groupname' => 'discentes'],
            [
                'groupname' => 'discentes',
                'attribute' => 'Simultaneous-Use',
                'op' => ':=',
                'value' => 1
            ]
        );

        ClientBond::insert([
            ['description' => 'Servidor', 'created_at' => now(), 'updated_at' => now(), 'radgroupcheck_id' => $servidores->id],
            ['description' => 'Discente', 'created_at' => now(), 'updated_at' => now(), 'radgroupcheck_id' => $discentes->id],
            ['description' => 'Colaborador', 'created_at' => now(), 'updated_at' => now(), 'radgroupcheck_id' => $colaboradores->id],
        ]);
    }
}

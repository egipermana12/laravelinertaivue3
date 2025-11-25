<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id'    => 1,
                'title' => 'Dashboard',
                'url'   => 'dashboard',
                'icon'  => 'SquareTerminal',
                'parentId' => 0,
                'sortOrder' => 1,
                'isActive' => true
            ],
            [
                'id'    => 2,
                'title' => 'Playground',
                'url'   => '#',
                'icon'  => 'SquareTerminal',
                'parentId' => 0,
                'sortOrder' => 2,
                'isActive' => false
            ],
            [
                'id'    => 3,
                'title' => 'History',
                'url'   => '#',
                'icon'  => 'SquareTerminal',
                'parentId' => 2,
                'sortOrder' => 1,
                'isActive' => false
            ],
            [
                'id'    => 4,
                'title' => 'Starred',
                'url'   => '#',
                'icon'  => 'SquareTerminal',
                'parentId' => 2,
                'sortOrder' => 2,
                'isActive' => false
            ],
            [
                'id'    => 5,
                'title' => 'Models',
                'url'   => '#',
                'icon'  => 'Bot',
                'parentId' => 0,
                'sortOrder' => 2,
                'isActive' => false
            ],
            [
                'id'    => 6,
                'title' => 'Genesis',
                'url'   => '#',
                'icon'  => 'SquareTerminal',
                'parentId' => 5,
                'sortOrder' => 1,
                'isActive' => false
            ],
            [
                'id'    => 7,
                'title' => 'Explorer',
                'url'   => '#',
                'icon'  => 'SquareTerminal',
                'parentId' => 5,
                'sortOrder' => 2,
                'isActive' => false
            ]
        ];
        DB::table('sidebars')->insert($data);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')
            ->insert(array(

                array('name' => 'Шымкент', 'order' => 0),

                array('title' => 'Караганда
', 'order' => 0),
                array('title' => 'Актобе
', 'order' => 0),
                array('title' => 'Тараз', 'order' => 0),
                array('title' => 'Павлодар', 'order' => 0),
                array('title' => 'Усть-Каменогорск', 'order' => 0),
                array('title' => 'Семей', 'order' => 0),
                array('title' => 'Костанай', 'order' => 0),
                array('title' => 'Атырау', 'order' => 0),
                array('title' => 'Кызылорда
', 'order' => 0),
                array('title' => 'Уральск
', 'order' => 0),
                array('title' => 'Петропавловск
', 'order' => 0),
                array('title' => 'Актау
', 'order' => 0),
                array('title' => 'Темиртау
', 'order' => 0),
                array('title' => 'Туркестан
', 'order' => 0),
                array('title' => 'Кокшетау
', 'order' => 0),
                array('title' => 'Талдыкорган
', 'order' => 0),
                array('title' => 'Экибастуз
', 'order' => 0),
                array('title' => 'Рудный
', 'order' => 0),


            ));
    }
}










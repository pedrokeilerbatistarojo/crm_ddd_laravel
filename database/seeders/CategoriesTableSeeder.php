<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /**
         * SELECT id, classification AS category, `name`, `order` AS priority, NOW() AS created_at, NOW() AS updated_at FROM tg_product_type;
         */
        \DB::unprepared(
            "
            INSERT INTO categories (`id`, `name`, `created_at`, `updated_at`)
            VALUES
                (1, 'Masajes-Tratamientos', '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (2, 'Promociones', '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (3, 'Tratamientos-Premium', '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (4, 'Circuitos', '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (5, 'General', '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (6, 'Belleza-Salud', '2022-05-25 17:49:36', '2022-05-25 17:49:36');
        "
        );

        \DB::unprepared("ALTER TABLE categories AUTO_INCREMENT = 7;");
    }
}

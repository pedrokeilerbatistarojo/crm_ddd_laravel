<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductTypesTableSeeder extends Seeder
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
            INSERT INTO product_types (`id`, `category_id`, `name`, `priority`, `created_at`, `updated_at`)
            VALUES
                (2, 1, 'TRATAMIENTOS FACIALES', 3, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (3, 1, 'MASAJES', 1, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (4, 1, 'TRATAMIENTOS CORPORALES - RITUALES', 4, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (5, 2, 'PACK', 2, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (6, 2, 'DUOS', 2, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (7, 3, 'TRATAMIENTOS PREMIUM', 3, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (10, 4, 'ENTRADAS', 1, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (11, 4, 'ENTRADA INFANTIL', 2, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (12, 4, 'ENTRADA PARA MAYORES DE 65 AÑOS', 3, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (26, 5, 'GENERAL', 1, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (29, 1, 'DEPURACIONES FACIALES', 2, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (30, 1, 'BONOS DE MASAJES', 6, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (36, 1, 'BODY SCRUBS', 5, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (39, 6, 'INDIBA', 1, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (40, 6, 'MESOTERAPIA VIRTUAL', 2, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (41, 6, 'PRESOTERAPIA', 3, '2022-05-25 17:49:36', '2022-05-25 17:49:36'),
                (42, 6, 'FOTOTERAPIA', 4, '2022-05-25 17:49:36', '2022-05-25 17:49:36');
        "
        );

        \DB::unprepared("ALTER TABLE product_types AUTO_INCREMENT = 43;");
    }
}

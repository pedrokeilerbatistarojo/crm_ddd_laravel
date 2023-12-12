<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var string
     */
    protected string $action = 'INSERT';

    /**
     * @var string
     */
    protected string $table = 'orders';

    /**
     * @var string
     */
    protected string $trigger_name = 'before_order_insert';

    /**
     * @var string
     */
    protected string $when = 'BEFORE';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $sql = "
            CREATE TRIGGER {$this->trigger_name} {$this->when} {$this->action} ON {$this->table} FOR EACH ROW

            BEGIN
                DECLARE CurrentGreatestValue INT;
                DECLARE CurrentGreatestTelephoneSaleSeqValue INT;

                IF IFNULL( NEW.ticket_number, 0) = 0 THEN
                	SET CurrentGreatestValue = (SELECT IFNULL( MAX(CAST(ticket_number AS SIGNED)), 400000 ) FROM orders);
                	SET NEW.ticket_number = CurrentGreatestValue + 1;
                END IF;

                IF NEW.type = \"Venta Telefónica\" THEN
                    SET CurrentGreatestTelephoneSaleSeqValue = (SELECT IFNULL( MAX(CAST(telephone_sale_seq AS SIGNED)), 10000 ) FROM orders);
                    SET NEW.telephone_sale_seq = CurrentGreatestTelephoneSaleSeqValue + 1;
                END IF;
            END;
        ";

        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::connection()->getPdo()->exec('DROP TRIGGER IF EXISTS ' . $this->trigger_name);
    }
};

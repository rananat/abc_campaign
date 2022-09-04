<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("status", 1);
            //$table->timestamps();
        });
        
        DB::statement("ALTER TABLE vouchers AUTO_INCREMENT = 1000;");

        //Procedure to fill 1000 rows in voucher table
        /*$procedure = "
            CREATE PROCEDURE `fill_voucher`()
                BEGIN
                DECLARE i INT;
                SELECT count(*) INTO i FROM `voucher`;
                WHILE (i < 1000) DO
                    INSERT INTO `voucher` (status) values ('U');
                    SET i = i+1;
                END WHILE;
            END
        ";

        DB::unprepared("DROP procedure IF EXISTS fill_voucher");
        DB::unprepared($procedure);

        DB::select('exec fill_voucher');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};

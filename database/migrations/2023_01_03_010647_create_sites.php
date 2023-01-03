<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('idSite');
            $table->unsignedBigInteger('idCustomer')->nullable();
            $table->string('siteUrl');
            $table->smallInteger('siteHealth')->default(0); // 0 unverified 1 success 2 error
            $table->smallInteger('siteStatus')->default(1);
            $table->string('siteCreatedBy');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('idCustomer', 'fk_sites_customers')->references('idCustomer')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}

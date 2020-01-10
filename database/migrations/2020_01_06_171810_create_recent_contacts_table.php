<?php

use App\Models\RecentContact;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecentContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recent_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('msg_id')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('target_id');
            $table->string('msg', RecentContact::MAX_CONTENT_LEN)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recent_contacts');
    }
}

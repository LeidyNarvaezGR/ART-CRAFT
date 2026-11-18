<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('cloudinary_public_id')->nullable()->after('imagen');
            $table->string('cloudinary_imagen_url')->nullable()->after('cloudinary_public_id');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['cloudinary_public_id', 'cloudinary_imagen_url']);
        });
    }
};

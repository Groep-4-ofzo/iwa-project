<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

abstract class BaseMigration extends Migration
{
    protected array $dependencies = [];

    public function __construct()
    {
        foreach ($this->dependencies as $migrationFile => $table) {
            if (! Schema::hasTable($table)) {
                (require database_path("migrations/{$migrationFile}.php"))->up();
            }
        }
    }
}

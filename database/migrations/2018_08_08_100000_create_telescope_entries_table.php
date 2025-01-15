<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        return config('telescope.storage.database.connection');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->create('telescope_entries', static function (Blueprint $collection) {
            $collection->unique('uuid');
            $collection->index('batch_id');
            $collection->index('family_hash');
            $collection->index('created_at');
            $collection->index(['type', 'should_display_on_index']);
        });

        $schema->create('telescope_entries_tags', static function (Blueprint $collection) {
            $collection->unique(['entry_uuid', 'tag']);
            $collection->index('tag');
        });

        $schema->create('telescope_monitoring', static function (Blueprint $collection) {
            $collection->index(columns: 'tag', options: ['unique' => true]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->dropIfExists('telescope_entries_tags');
        $schema->dropIfExists('telescope_entries');
        $schema->dropIfExists('telescope_monitoring');
    }
};

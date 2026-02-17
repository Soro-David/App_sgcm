<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class UpdateFinancierRole extends Command
{
    protected $signature = 'app:update-financier-role';
    protected $description = 'Replace role "financié" with "financiers" in all tables';

    public function handle()
    {
        $tables = ['mairies', 'finances', 'financiers', 'agents'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'role')) {
                $count = DB::table($table)->where('role', 'financié')->update(['role' => 'financiers']);
                $this->info("Updated $count records in $table table (role column).");
            }
        }

        if (Schema::hasTable('agents') && Schema::hasColumn('agents', 'type')) {
            $count = DB::table('agents')->where('type', 'financié')->update(['type' => 'financiers']);
            $this->info("Updated $count records in agents table (type column).");
        }

        return Command::SUCCESS;
    }
}

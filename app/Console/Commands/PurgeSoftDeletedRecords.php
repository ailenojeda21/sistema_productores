<?php

namespace App\Console\Commands;

use App\Models\StaffUser;
use Illuminate\Console\Command;

class PurgeSoftDeletedRecords extends Command
{
    protected $signature = 'app:purge-soft-deleted-records
        {--days=365 : Days after which soft-deleted records are permanently deleted}
        {--dry-run : List records that would be purged without actually deleting}';

    protected $description = 'Permanently purge soft-deleted records older than the specified days';

    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');
        $cutoff = now()->subDays($days);

        $this->info("Purging soft-deleted records older than {$days} days (before {$cutoff->toDateTimeString()})...");

        $this->purgeStaffUsers($cutoff, $dryRun);

        $this->info('Done.');
    }

    private function purgeStaffUsers($cutoff, bool $dryRun): void
    {
        $query = StaffUser::onlyTrashed()->where('deleted_at', '<', $cutoff);
        $count = $query->count();

        if ($count === 0) {
            $this->info('No expired staff users to purge.');

            return;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would purge {$count} staff user(s):");
            $query->each(fn (StaffUser $user) => $this->line("  - {$user->id}: {$user->name} ({$user->email}), deleted {$user->deleted_at}"));
        } else {
            $query->forceDelete();
            $this->info("Purged {$count} staff user(s).");
        }
    }
}

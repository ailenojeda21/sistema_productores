<?php

namespace App\Console\Commands;

use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

        $this->purgeUsers($cutoff, $dryRun);
        $this->purgeStaffUsers($cutoff, $dryRun);

        $this->info('Done.');
    }

    private function purgeStaffUsers($cutoff, bool $dryRun): void
    {
        $query = StaffUser::onlyTrashed()->where('deleted_at', '<', $cutoff);
        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info('No expired staff users to purge.');

            return;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would purge {$count} staff user(s):");
            $query->each(fn (StaffUser $user) => $this->line("  - {$user->id}: {$user->name}, deleted {$user->deleted_at}"));
        } else {
            $query->forceDelete();
            $this->info("Purged {$count} staff user(s).");
        }
    }

    private function purgeUsers($cutoff, bool $dryRun): void
    {
        $query = User::onlyTrashed()->where('deleted_at', '<', $cutoff)
            ->with(['propiedades' => fn ($q) => $q->select('id', 'usuario_id', 'rut_archivo')]);

        $users = $query->get();
        $count = $users->count();

        if ($count === 0) {
            $this->info('No expired users to purge.');

            return;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would permanently delete {$count} user(s) and their data:");
            foreach ($users as $user) {
                $this->line("  - User {$user->id}, deleted {$user->deleted_at}");
                foreach ($user->propiedades as $propiedad) {
                    $this->line("      Propiedad {$propiedad->id}".
                        ($propiedad->rut_archivo ? " + RUT file [{$propiedad->rut_archivo}]" : ''));
                }
            }

            return;
        }

        $rutFiles = $users->flatMap->propiedades
            ->pluck('rut_archivo')
            ->filter()
            ->unique()
            ->values();

        $users->each(fn (User $user) => $user->forceDelete());
        $this->info("Purged {$count} user(s).");

        if ($rutFiles->isNotEmpty()) {
            Storage::disk('rut_files')->delete($rutFiles->all());
            $this->info("Deleted {$rutFiles->count()} RUT file(s) from storage.");
        }
    }
}

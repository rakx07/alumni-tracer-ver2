<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PortalDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user?->role ?? 'user';

        // ✅ Officer/Admin dashboard
        if (in_array($role, ['admin', 'it_admin', 'alumni_officer'], true)) {

            $stats = $this->buildStatsFromAlumnusModel();

            return view('portal.dashboard', compact('stats'));
        }

        // ✅ User dashboard
        $alumnus = Alumnus::where('user_id', Auth::id())->first();
        return view('user.dashboard', compact('alumnus'));
    }

    /**
     * Build dashboard stats using the Alumnus model.
     * - total_records
     * - new_this_month (based on created_at if available)
     * - with_email (tries common email columns)
     * - with_contact (tries common contact columns)
     */
    private function buildStatsFromAlumnusModel(): array
    {
        $model = new Alumnus();
        $table = $model->getTable();

        // Default safe output (never crash the dashboard)
        $blank = [
            'total_records'  => '—',
            'new_this_month' => '—',
            'with_email'     => '—',
            'with_contact'   => '—',
        ];

        if (!Schema::hasTable($table)) {
            return $blank;
        }

        // Detect columns
        $emailCol   = $this->firstExistingColumn($table, ['email', 'email_address', 'personal_email', 'school_email']);
        $contactCol = $this->firstExistingColumn($table, ['contact_no', 'contact_number', 'mobile', 'mobile_no', 'phone', 'phone_number']);
        $dateCol    = $this->firstExistingColumn($table, ['created_at', 'submitted_at', 'updated_at']);

        // Total records
        $total = Alumnus::query()->count();

        // New this month (uses whichever date column exists)
        $newThisMonth = null;
        if ($dateCol) {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();

            $newThisMonth = Alumnus::query()
                ->whereBetween($dateCol, [$start, $end])
                ->count();
        }

        // With email
        $withEmail = null;
        if ($emailCol) {
            $withEmail = Alumnus::query()
                ->whereNotNull($emailCol)
                ->where($emailCol, '!=', '')
                ->count();
        }

        // With contact
        $withContact = null;
        if ($contactCol) {
            $withContact = Alumnus::query()
                ->whereNotNull($contactCol)
                ->where($contactCol, '!=', '')
                ->count();
        }

        return [
            'total_records'  => number_format($total),
            'new_this_month' => is_null($newThisMonth) ? '—' : number_format($newThisMonth),
            'with_email'     => is_null($withEmail) ? '—' : number_format($withEmail),
            'with_contact'   => is_null($withContact) ? '—' : number_format($withContact),
        ];
    }

    private function firstExistingColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }
        return null;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\SignIn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * The home page.
 *
 * Everyone sees their own corner (account status, their recent activity,
 * announcements). People with "dashboard.view" also see the system figures.
 * Add your module's cards in resources/views/dashboard.blade.php.
 */
class DashboardController extends Controller
{
    private const DAYS = 14;

    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $canSeeFigures = $user->hasAccess('dashboard.view');

        $data = [
            'user' => $user,
            'canSeeFigures' => $canSeeFigures,
            'announcements' => Announcement::visible()->latest('updated_at')->limit(3)->get(),
            'myActivity' => ActivityLog::where('causer_id', $user->id)->latest('id')->limit(6)->get(),
            'lastSignIn' => SignIn::where('user_id', $user->id)->where('successful', true)
                ->where('session_id', '!=', $request->session()->getId())->latest('id')->first(),
        ];

        if ($canSeeFigures) {
            $data += $this->figures($user);
        }

        return view('dashboard', $data);
    }

    private function figures(User $user): array
    {
        $tz = display_timezone();
        $todayStart = now($tz)->startOfDay()->utc();
        $since = now($tz)->subDays(self::DAYS - 1)->startOfDay()->utc();

        $stats = [
            'users' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'online' => User::where('last_activity_at', '>=', now()->subMinutes(5))->count(),
            'two_factor' => User::whereNotNull('two_factor_confirmed_at')->count(),
            'signins_today' => SignIn::where('successful', true)->where('created_at', '>=', $todayStart)->count(),
            'failed_today' => SignIn::where('successful', false)->where('created_at', '>=', $todayStart)->count(),
            'new_users_30d' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'actions_today' => ActivityLog::where('created_at', '>=', $todayStart)->count(),
        ];

        // Sign-ins per day (grouped in PHP so it works the same on MySQL, PostgreSQL and SQLite).
        $days = collect(range(self::DAYS - 1, 0))->mapWithKeys(fn ($i) => [now($tz)->subDays($i)->format('Y-m-d') => ['ok' => 0, 'failed' => 0]]);
        SignIn::where('created_at', '>=', $since)->select(['created_at', 'successful'])->cursor()
            ->each(function (SignIn $row) use (&$days, $tz) {
                $day = Carbon::parse($row->created_at)->setTimezone($tz)->format('Y-m-d');
                if ($days->has($day)) {
                    $entry = $days[$day];
                    $entry[$row->successful ? 'ok' : 'failed']++;
                    $days[$day] = $entry;
                }
            });

        return [
            'stats' => $stats,
            'chart' => [
                'labels' => $days->keys()->map(fn ($d) => Carbon::parse($d)->translatedFormat('d M'))->values(),
                'ok' => $days->pluck('ok')->values(),
                'failed' => $days->pluck('failed')->values(),
            ],
            'recentActivity' => ActivityLog::with(['causer', 'subject'])->latest('id')->limit(8)->get(),
            'recentUsers' => User::latest('id')->limit(5)->get(),
            'onlineUsers' => $user->hasAccess('presence.view')
                ? User::where('last_activity_at', '>=', now()->subMinutes(5))->orderByDesc('last_interaction_at')->limit(8)->get()
                : collect(),
        ];
    }
}

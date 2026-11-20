<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Builds the sidebar from config/menu.php for one user: hides what they
 * can't open, drops empty sections, and marks the active item.
 */
class Menu
{
    /** @return array<int, array> */
    public static function for(?User $user): array
    {
        $items = self::filter(config('menu', []), $user);

        // Drop headings that have nothing under them before the next heading.
        $out = [];
        foreach ($items as $i => $item) {
            if (isset($item['heading'])) {
                $next = $items[$i + 1] ?? null;
                if (! $next || isset($next['heading'])) {
                    continue;
                }
            }
            $out[] = $item;
        }

        return $out;
    }

    private static function filter(array $items, ?User $user): array
    {
        $out = [];
        foreach ($items as $item) {
            if (isset($item['heading'])) {
                $out[] = $item;

                continue;
            }
            if (! empty($item['can']) && ! $user?->hasAccess($item['can'])) {
                continue;
            }

            if (! empty($item['children'])) {
                $item['children'] = self::filter($item['children'], $user);
                if (! $item['children']) {
                    continue;
                }
                $item['active'] = collect($item['children'])->contains('active', true);
            } else {
                if (empty($item['route']) || ! Route::has($item['route'])) {
                    continue;
                }
                $item['url'] = route($item['route']);
                $item['active'] = request()->routeIs(...(array) ($item['active'] ?? [$item['route'], $item['route'].'.*']));
            }

            $out[] = $item;
        }

        return array_values($out);
    }
}

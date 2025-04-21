<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $tasks = [];
        if (auth()->user() && auth()->user()->admin == 1){
            $tasks = \App\Models\Task::select(['id', 'title', 'desc', 'due_date'])
                ->whereDate('due_date', now())
                ->whereNull('completed_at')
                ->get();
        }

        return [
            ...parent::share($request),
            'user' => $request->user(),
            'base_img_path' => asset('images') . '/',
            'setting' => \App\Models\Setting::first(),
            'tasks' => $tasks
        ];
    }
}

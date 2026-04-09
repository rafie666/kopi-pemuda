<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function logActivity($message)
    {
        $user = auth()->user();
        $userName = $user ? $user->name : 'Sistem';
        
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\CashierActionNotification($userName, $message));
        }
    }
}

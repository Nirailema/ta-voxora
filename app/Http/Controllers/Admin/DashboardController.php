<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_documents' => Document::count(),
            'total_conversations' => Conversation::count(),
            'ready_documents' => Document::where('status', 'ready')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

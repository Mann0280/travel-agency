<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\AccountContent;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show($type)
    {
        $validTypes = ['faq', 'about', 'policy'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $contents = AccountContent::where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $title = ucfirst($type);
        if ($type === 'faq') $title = 'Frequently Asked Questions';
        if ($type === 'about') $title = 'About ZUBEE';
        if ($type === 'policy') $title = 'Terms & Policies';

        return view('frontend.content', compact('contents', 'title', 'type'));
    }
}

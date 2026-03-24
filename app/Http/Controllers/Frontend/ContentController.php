<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\AccountContent;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show($type)
    {
        $query = AccountContent::where('is_active', true);
        
        if ($type === 'terms' || $type === 'policy' || $type === 'privacy') {
            $slug = ($type === 'privacy') ? 'privacy' : 'terms';
            $contents = $query->where('slug', $slug)->orderBy('sort_order')->get();
            // Fallback to type if specific slug not found
            if ($contents->isEmpty()) {
                $contents = AccountContent::where('type', 'policy')->where('is_active', true)->orderBy('sort_order')->get();
            }
        } else {
            $contents = $query->where('type', $type)->orderBy('sort_order')->get();
        }

        $title = ucfirst($type);
        if ($type === 'faq') $title = 'Frequently Asked Questions';
        if ($type === 'about') $title = 'About ZUBEE';
        if ($type === 'policy' || $type === 'terms') {
            $title = 'Terms & Conditions';
            $type = 'policy'; 
        }
        if ($type === 'privacy') {
            $title = 'Privacy Policy';
            $type = 'policy';
        }

        return view('frontend.content', compact('contents', 'title', 'type'));
    }
}

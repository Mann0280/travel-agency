<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountContent;
use App\Models\Faq;
use App\Models\FeedbackCategory;
use Illuminate\Http\Request;

class AccountContentController extends Controller
{
    public function index()
    {
        $faqCount = Faq::count();
        $activeFaqCount = Faq::where('status', 'active')->count();
        
        $about = AccountContent::where('slug', 'about')->first();
        $aboutData = $about ? $about->data : [];
        $valueCount = count($aboutData['values'] ?? []);
        
        $partner = AccountContent::where('slug', 'partner')->first();
        $partnerData = $partner ? $partner->data : [];
        $benefitCount = count($partnerData['benefits'] ?? []);
        
        $feedbackSettings = AccountContent::where('slug', 'feedback_settings')->first();
        $categoryCount = FeedbackCategory::count();

        return view('admin.account-content.index', compact(
            'faqCount', 'activeFaqCount', 'aboutData', 'valueCount',
            'partnerData', 'benefitCount', 'categoryCount'
        ));
    }

    // About Us
    public function about()
    {
        $about = AccountContent::firstOrCreate(['slug' => 'about'], [
            'data' => [
                'mission' => 'Make extraordinary travel accessible with curated packages and quality service.',
                'values' => [
                    'Integrity & Transparency',
                    'Excellence in Service',
                    'Continuous Innovation',
                    'Sustainable Tourism',
                    'Customer Focus'
                ],
                'why_choose' => [
                    '25+ years experience',
                    '150+ destinations',
                    '10,000+ travelers',
                    '24/7 support'
                ],
                'description' => 'Creating unforgettable travel experiences for over 25 years.'
            ]
        ]);
        
        $aboutData = $about->data;
        return view('admin.account-content.about', compact('aboutData'));
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'mission' => 'required|string',
            'description' => 'required|string',
            'values' => 'required|string',
            'why_choose' => 'required|string',
        ]);

        $values = array_filter(array_map('trim', explode("\n", $validated['values'])));
        $why_choose = array_filter(array_map('trim', explode("\n", $validated['why_choose'])));

        if (count($values) < 3 || count($why_choose) < 3) {
            return back()->withErrors(['error' => 'Please add at least 3 values and 3 reasons to choose us.'])->withInput();
        }

        $about = AccountContent::where('slug', 'about')->first();
        $about->update([
            'data' => [
                'mission' => $validated['mission'],
                'description' => $validated['description'],
                'values' => array_values($values),
                'why_choose' => array_values($why_choose),
                'updated_at' => now()->toDateTimeString()
            ]
        ]);

        return redirect()->route('admin.account-content.about')->with('success', 'About Us content updated successfully!');
    }

    // FAQ
    public function faq()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        return view('admin.account-content.faq', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'sort_order' => $validated['sort_order'],
            'status' => 'active',
        ]);

        return redirect()->route('admin.account-content.faq')->with('success', 'FAQ added successfully!');
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        
        if ($request->has('toggle_status')) {
            $faq->update(['status' => $faq->status === 'active' ? 'inactive' : 'active']);
            return redirect()->route('admin.account-content.faq')->with('success', 'FAQ status toggled!');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'sort_order' => $validated['sort_order'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.account-content.faq')->with('success', 'FAQ updated successfully!');
    }

    public function destroyFaq($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.account-content.faq')->with('success', 'FAQ deleted successfully!');
    }

    // Partner
    public function partner()
    {
        $partner = AccountContent::firstOrCreate(['slug' => 'partner'], [
            'data' => [
                'benefits' => [
                    'Access to our customer base',
                    'Marketing support',
                    'Streamlined booking',
                    'Real-time management'
                ],
                'requirements' => [
                    'Registered travel agency',
                    'Valid business license',
                    '2+ years operation',
                    'Positive reviews'
                ],
                'description' => 'Join ZUBEEE\'s network of trusted travel partners.',
                'apply_button_text' => 'Apply Now',
                'contact_email' => 'partnerships@zubeee.com',
                'contact_phone' => '+1 234 567 8900'
            ]
        ]);
        
        $partnerData = $partner->data;
        return view('admin.account-content.partner', compact('partnerData'));
    }

    public function updatePartner(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'apply_button_text' => 'required|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'benefits' => 'required|string',
            'requirements' => 'required|string',
        ]);

        $benefits = array_filter(array_map('trim', explode("\n", $validated['benefits'])));
        $requirements = array_filter(array_map('trim', explode("\n", $validated['requirements'])));

        if (count($benefits) < 2 || count($requirements) < 2) {
            return back()->withErrors(['error' => 'Please add at least 2 benefits and 2 requirements.'])->withInput();
        }

        $partner = AccountContent::where('slug', 'partner')->first();
        $partner->update([
            'data' => [
                'description' => $validated['description'],
                'apply_button_text' => $validated['apply_button_text'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'benefits' => array_values($benefits),
                'requirements' => array_values($requirements),
                'updated_at' => now()->toDateTimeString()
            ]
        ]);

        return redirect()->route('admin.account-content.partner')->with('success', 'Partner content updated successfully!');
    }

    // Feedback
    public function feedback()
    {
        $feedbackSettings = AccountContent::firstOrCreate(['slug' => 'feedback_settings'], [
            'data' => [
                'default_feedback' => 'I recently booked the Bali Tropical Escape and had an amazing time...',
                'updated_at' => now()->toDateTimeString()
            ]
        ]);
        
        $feedbackData = [
            'categories' => FeedbackCategory::pluck('label', 'key')->toArray(),
            'default_feedback' => $feedbackSettings->data['default_feedback'] ?? ''
        ];
        
        $categories = FeedbackCategory::all();

        return view('admin.account-content.feedback', compact('feedbackData', 'categories'));
    }

    public function updateFeedbackSettings(Request $request)
    {
        $validated = $request->validate([
            'default_feedback' => 'required|string',
        ]);

        $settings = AccountContent::where('slug', 'feedback_settings')->first();
        $settings->update([
            'data' => [
                'default_feedback' => $validated['default_feedback'],
                'updated_at' => now()->toDateTimeString()
            ]
        ]);

        return redirect()->route('admin.account-content.feedback')->with('success', 'Feedback settings updated!');
    }

    public function storeFeedbackCategory(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:feedback_categories,key',
            'label' => 'required|string',
        ]);

        FeedbackCategory::create([
            'key' => $validated['key'],
            'label' => $validated['label'],
        ]);

        return redirect()->route('admin.account-content.feedback')->with('success', 'Category added!');
    }

    public function updateFeedbackCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'label' => 'required|string',
        ]);

        FeedbackCategory::findOrFail($id)->update([
            'label' => $validated['label'],
        ]);

        return redirect()->route('admin.account-content.feedback')->with('success', 'Category updated!');
    }

    public function destroyFeedbackCategory($id)
    {
        FeedbackCategory::findOrFail($id)->delete();
        return redirect()->route('admin.account-content.feedback')->with('success', 'Category deleted!');
    }
}

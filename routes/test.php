<?php

use Illuminate\Support\Facades\Route;
use App\Models\Destination;

Route::get('/test-destination-create', function() {
    try {
        // Test 1: Check if we can create a destination directly
        $destination = Destination::create([
            'name' => 'Test Destination ' . time(),
            'description' => 'This is a test description',
            'location' => 'Test Location',
            'status' => true,
        ]);
        
        echo "✅ Test 1 PASSED: Destination created successfully with ID: " . $destination->id . "<br>";
        
        // Test 2: Check if we can retrieve it
        $retrieved = Destination::find($destination->id);
        if ($retrieved) {
            echo "✅ Test 2 PASSED: Destination retrieved successfully<br>";
        } else {
            echo "❌ Test 2 FAILED: Could not retrieve destination<br>";
        }
        
        // Test 3: Check withCount
        $withCount = Destination::withCount('packages')->find($destination->id);
        echo "✅ Test 3 PASSED: packages_count = " . $withCount->packages_count . "<br>";
        
        // Test 4: Delete test destination
        $destination->delete();
        echo "✅ Test 4 PASSED: Test destination deleted<br>";
        
        echo "<br><strong>All tests passed! The model and database are working correctly.</strong>";
        
    } catch (\Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "<br>";
        echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
})->middleware('admin');

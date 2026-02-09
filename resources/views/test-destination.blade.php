<!DOCTYPE html>
<html>
<head>
    <title>Test Destination Form</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { min-height: 100px; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Test Destination Creation Form</h1>
    <p>This is a simple test form to verify destination creation works.</p>
    
    <form action="http://127.0.0.1:8000/admin/destinations" method="POST">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        
        <div class="form-group">
            <label>Name (Required)</label>
            <input type="text" name="name" value="Test Destination <?php echo time(); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Location (Required)</label>
            <input type="text" name="location" value="Test Location, India" required>
        </div>
        
        <div class="form-group">
            <label>Description (Required)</label>
            <textarea name="description" required>This is a test description for the destination. It contains enough text to be valid.</textarea>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="Adventure">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="status" checked> Active
            </label>
        </div>
        
        <button type="submit">Create Test Destination</button>
    </form>
    
    <hr style="margin: 30px 0;">
    
    <h2>Recent Destinations</h2>
    <?php
        $destinations = \App\Models\Destination::latest()->take(5)->get();
        if ($destinations->count() > 0) {
            echo '<ul>';
            foreach ($destinations as $dest) {
                echo '<li><strong>' . $dest->name . '</strong> - ' . $dest->location . ' (ID: ' . $dest->id . ')</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No destinations found.</p>';
        }
    ?>
</body>
</html>

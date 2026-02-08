<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $settings = SystemSetting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return redirect()->back()->with('error', 'Unauthorized access. Admin privileges required.');
            }

            // Build validation rules dynamically based on what fields are present
            $validationRules = [];

            // Main library configuration settings (if provided)
            if ($request->has('borrowing_duration_days')) {
                $validationRules['borrowing_duration_days'] = 'required|integer|min:1|max:365';
            }
            if ($request->has('max_renewals')) {
                $validationRules['max_renewals'] = 'required|integer|min:0|max:10';
            }
            if ($request->has('fine_per_day')) {
                $validationRules['fine_per_day'] = 'required|numeric|min:0|max:1000';
            }
            if ($request->has('max_books_per_user')) {
                $validationRules['max_books_per_user'] = 'required|integer|min:1|max:20';
            }
            if ($request->has('grace_period_days')) {
                $validationRules['grace_period_days'] = 'required|integer|min:0|max:30';
            }
            if ($request->has('max_overdue_days')) {
                $validationRules['max_overdue_days'] = 'required|integer|min:1|max:365';
            }
            if ($request->has('weekend_due_dates')) {
                $validationRules['weekend_due_dates'] = 'required|string|in:allow,move_to_monday,move_to_friday';
            }
            if ($request->has('holiday_handling')) {
                $validationRules['holiday_handling'] = 'required|string|in:extend,strict';
            }
            if ($request->has('due_date_reminder_days')) {
                $validationRules['due_date_reminder_days'] = 'required|integer|min:1|max:14';
            }
            if ($request->has('overdue_notification_days')) {
                $validationRules['overdue_notification_days'] = 'required|integer|min:0|max:7';
            }

            // Library information fields (if provided)
            if ($request->has('library_hours')) {
                $validationRules['library_hours'] = 'required|string|max:1000';
            }
            if ($request->has('library_location')) {
                $validationRules['library_location'] = 'required|string|max:255';
            }
            if ($request->has('featured_books_text')) {
                $validationRules['featured_books_text'] = 'required|string|max:2000';
            }

            $request->validate($validationRules);

            // Update settings only for fields that are present in the request
            $settingsToUpdate = [];

            // Main library configuration settings
            $mainSettingsFields = [
                'borrowing_duration_days', 'max_renewals', 'fine_per_day', 'max_books_per_user',
                'grace_period_days', 'max_overdue_days', 'weekend_due_dates', 'holiday_handling',
                'due_date_reminder_days', 'overdue_notification_days'
            ];

            foreach ($mainSettingsFields as $field) {
                if ($request->has($field)) {
                    $settingsToUpdate[$field] = $request->$field;
                }
            }

            // Handle boolean fields specially (removed - no longer used in form)

            // Library information settings
            $libraryInfoFields = ['library_hours', 'library_location', 'featured_books_text'];
            foreach ($libraryInfoFields as $field) {
                if ($request->has($field)) {
                    $settingsToUpdate[$field] = $request->$field;
                }
            }

            // Save the settings
            foreach ($settingsToUpdate as $key => $value) {
                $type = 'string'; // Default type

                // Numeric fields
                if (in_array($key, ['borrowing_duration_days', 'max_renewals', 'fine_per_day', 'max_books_per_user', 'grace_period_days', 'max_overdue_days'])) {
                    $type = 'number';
                }
                // Boolean fields
                elseif (in_array($key, ['self_service_enabled', 'email_notifications_enabled'])) {
                    $type = 'boolean';
                }
                // String fields (dropdowns)
                elseif (in_array($key, ['weekend_due_dates', 'holiday_handling'])) {
                    $type = 'string';
                }

                SystemSetting::set($key, $value, $type);
            }

            // Determine which type of update was performed
            $hasLibrarySettings = $request->hasAny(['library_hours', 'library_location', 'featured_books_text']);
            $hasMainSettings = $request->hasAny(['borrowing_duration_days', 'max_renewals', 'fine_per_day', 'max_books_per_user', 'grace_period_days', 'max_overdue_days', 'weekend_due_dates', 'holiday_handling', 'self_service_enabled', 'email_notifications_enabled']);

            if ($hasLibrarySettings && !$hasMainSettings) {
                $message = 'Library information updated successfully!';
            } elseif ($hasMainSettings && !$hasLibrarySettings) {
                $message = 'System settings updated successfully!';
            } else {
                $message = 'All settings updated successfully!';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('System Settings Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating settings. Please try again.');
        }
    }

    public function reset(Request $request)
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return redirect()->back()->with('error', 'Unauthorized access. Admin privileges required.');
            }

            // Default settings values
            $defaultSettings = [
                // Library configuration settings
                'borrowing_duration_days' => ['value' => 14, 'type' => 'number'],
                'max_renewals' => ['value' => 2, 'type' => 'number'],
                'fine_per_day' => ['value' => 5.00, 'type' => 'number'],
                'max_books_per_user' => ['value' => 3, 'type' => 'number'],
                'grace_period_days' => ['value' => 3, 'type' => 'number'],
                'max_overdue_days' => ['value' => 30, 'type' => 'number'],
                'weekend_due_dates' => ['value' => 'move_to_monday', 'type' => 'string'],
                'holiday_handling' => ['value' => 'extend', 'type' => 'string'],
                'due_date_reminder_days' => ['value' => 3, 'type' => 'number'],
                'overdue_notification_days' => ['value' => 1, 'type' => 'number'],

                // Library information settings
                'library_hours' => ['value' => "Monday - Friday: 7:00 AM - 5:00 PM\nSaturday: 8:00 AM - 12:00 PM\nSunday: Closed", 'type' => 'string'],
                'library_location' => ['value' => 'Dagupan City National High School, Dagupan City, Pangasinan', 'type' => 'string'],
                'featured_books_text' => ['value' => 'Discover our most popular and recently added books. From classic literature to modern bestsellers, find your next great read in our carefully curated collection.', 'type' => 'string'],
            ];

            // Reset all settings to defaults
            foreach ($defaultSettings as $key => $config) {
                SystemSetting::set($key, $config['value'], $config['type']);
            }

            return redirect()->back()->with('success', 'All system settings have been reset to their default values!');

        } catch (\Exception $e) {
            \Log::error('System Settings Reset Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while resetting settings. Please try again.');
        }
    }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Seeder - LibraFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Database Seeder</h1>
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">← Back to Dashboard</a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Current Database Status -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-blue-600">Users</h3>
                        <p class="text-2xl font-bold text-blue-800">{{ $users }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-green-600">Books</h3>
                        <p class="text-2xl font-bold text-green-800">{{ $books }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-purple-600">Authors</h3>
                        <p class="text-2xl font-bold text-purple-800">{{ $authors }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-yellow-600">Categories</h3>
                        <p class="text-2xl font-bold text-yellow-800">{{ $categories }}</p>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Warning:</strong> Running these seeders will overwrite existing data. 
                                This should only be used for initial setup or testing.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Seeder Options -->
                <div class="space-y-6">
                    <!-- Run All Seeders -->
                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Run All Seeders</h3>
                        <p class="text-gray-600 mb-4">
                            This will create an admin user, populate books, and set up system settings.
                        </p>
                        <form method="POST" action="{{ route('database-seeder.run-all') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                onclick="return confirm('Are you sure you want to run ALL seeders? This will overwrite existing data!')"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Run All Seeders
                            </button>
                        </form>
                    </div>

                    <!-- Individual Seeders -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Admin User Seeder -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Admin User</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Creates admin user: admin@libraflow.com / admin123
                            </p>
                            <form method="POST" action="{{ route('database-seeder.admin') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                    onclick="return confirm('Create admin user?')"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Create Admin
                                </button>
                            </form>
                        </div>

                        <!-- Books Seeder -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Sample Books</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Creates 60+ college textbooks across various subjects
                            </p>
                            <form method="POST" action="{{ route('database-seeder.books') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                    onclick="return confirm('Create sample books? This will clear existing books!')"
                                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Create Books
                                </button>
                            </form>
                        </div>

                        <!-- Settings Seeder -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">System Settings</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Sets up default library system configuration
                            </p>
                            <form method="POST" action="{{ route('database-seeder.settings') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                    onclick="return confirm('Create system settings?')"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Create Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Instructions</h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                        <li><strong>For Initial Setup:</strong> Click "Run All Seeders" to create everything at once</li>
                        <li><strong>Admin Access:</strong> Use admin@libraflow.com / admin123 to log in</li>
                        <li><strong>Individual Seeding:</strong> Use the individual buttons if you need to reset specific parts</li>
                        <li><strong>Security:</strong> Change the admin password immediately after first login</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

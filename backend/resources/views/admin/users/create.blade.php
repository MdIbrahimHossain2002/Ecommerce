<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Create New User</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</body>
</html>
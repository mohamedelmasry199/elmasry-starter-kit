<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.contact') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">{{ __('messages.contact') }}</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.subject') }}</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('subject') border-red-500 @enderror">
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.message') }}</label>
                    <textarea name="message" id="message" rows="5"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                    {{ __('messages.send') }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>

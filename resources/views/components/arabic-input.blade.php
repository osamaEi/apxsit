<!-- resources/views/components/arabic-input.blade.php -->
<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1 relative rounded-md shadow-sm">
        <input
            type="{{ $type ?? 'text' }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder ?? '' }}"
            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md {{ $errors->has($name) ? 'border-red-300' : '' }}"
            {{ $required ?? false ? 'required' : '' }}
            {{ $attributes }}
            dir="rtl"
            lang="ar"
            onkeypress="return /[\u0600-\u06FF\s]/i.test(event.key)"
            oninput="validateArabicInput(this)"
        />
        @if($errors->has($name))
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    
    @if($errors->has($name))
        <p class="mt-2 text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif

    <script>
        function validateArabicInput(input) {
            // Remove any non-Arabic characters
            input.value = input.value.replace(/[^\u0600-\u06FF\s]/g, '');
        }
    </script>
</div>
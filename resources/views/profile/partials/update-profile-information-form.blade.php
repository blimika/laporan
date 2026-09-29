<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->satker && !$user->satker->is_centralized_api)
        <div class="pt-4 border-t border-gray-200 mt-4">
            <h3 class="text-md font-medium text-gray-900 mb-4">Pengaturan API Key Pribadi</h3>
            <p class="text-sm text-gray-600 mb-4">Satker Anda dikonfigurasi untuk menggunakan API Key masing-masing user. Silakan masukkan API Key Anda di bawah ini agar dapat menggunakan fitur AI.</p>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="gemini_api_key" :value="__('Google Gemini API Key')" />
                    <x-text-input id="gemini_api_key" name="gemini_api_key" type="password" class="mt-1 block w-full" :value="old('gemini_api_key', $user->gemini_api_key)" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gemini_api_key')" />
                </div>

                <div>
                    <x-input-label for="deepseek_api_key" :value="__('Deepseek API Key')" />
                    <x-text-input id="deepseek_api_key" name="deepseek_api_key" type="password" class="mt-1 block w-full" :value="old('deepseek_api_key', $user->deepseek_api_key)" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('deepseek_api_key')" />
                </div>
            </div>
        </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

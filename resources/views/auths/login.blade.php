<x-layouts.auth>
    <x-slot:title>
        {{ __('messages.auth.login') }}
    </x-slot:title>

    <x-cards.card class="min-w-[300px] w-[400px] p-6">
        <form action="{{ route('post.login') }}" method="post">
            @csrf
            <x-misc.error-message />
            
            <div class="flex flex-col gap-y-2 mb-4">
                <x-labels.label for="email">{{ __('messages.auth.email') }}</x-labels.label>
                <x-inputs.input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('messages.auth.email_placeholder') }}" />
            </div>

            <div class="flex flex-col gap-y-2 mb-4">
                <x-labels.label for="password">{{ __('messages.auth.password') }}</x-labels.label>
                <x-inputs.input type="password" name="password" id="password" placeholder="{{ __('messages.auth.password_placeholder') }}" />
            </div>

            <div class="flex items-center gap-x-2 mb-6">
                <x-inputs.input type="checkbox" name="remember" id="remember" class="cursor-pointer" :checked="old('remember')" />
                <x-labels.label for="remember">{{ __('messages.auth.remember_me') }}</x-labels.label>
            </div>

            <x-buttons.primary class="w-full">{{ __('messages.auth.login_button') }}</x-buttons.primary>
        </form>
    </x-cards.card>

</x-layouts.auth>

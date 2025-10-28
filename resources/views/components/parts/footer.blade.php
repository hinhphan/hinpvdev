<div class="relative flex flex-col-reverse md:flex-row items-center justify-between p-6 md:p-4 gap-2">
    <x-misc.devider-top />
    <div class="flex items-center">
        <p class="text-center md:text-left">
            {{ __('messages.footer.copyright', ['year' => date('Y'), 'name' => 'HinPV']) }}
        </p>
    </div>
    <x-misc.social-links />
</div>

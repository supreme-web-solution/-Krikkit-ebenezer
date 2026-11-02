<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <studio:card>
        <p class="mb-4 text-sm font-medium text-studio-muted">{{ __('dashboard.Buttons') }}</p>
        <div class="flex flex-wrap items-center gap-2">
            <studio:button variant="outline">{{ __('dashboard.Button') }}</studio:button>
            <studio:button variant="primary">{{ __('dashboard.Primary') }}</studio:button>
            <studio:button variant="fill">{{ __('dashboard.Filled') }}</studio:button>
        </div>
    </studio:card>

    <studio:card>
        <p class="mb-1 text-sm font-semibold text-studio-fg">{{ __('dashboard.Select your payment method') }}</p>
        <p class="mb-4 text-xs text-studio-muted">{{ __('dashboard.Radio group') }}</p>
        <div class="space-y-3">
            <studio:radio name="theme_pay" value="card" :label="__('dashboard.Credit Card')" checked />
            <studio:radio name="theme_pay" value="paypal" :label="__('dashboard.Paypal')" />
            <studio:radio name="theme_pay" value="bank" :label="__('dashboard.Bank transfer')" />
        </div>
    </studio:card>

    <studio:card>
        <p class="mb-1 text-sm font-semibold text-studio-fg">{{ __('dashboard.Role') }}</p>
        <p class="mb-4 text-xs text-studio-muted">{{ __('dashboard.Checklist') }}</p>
        <div class="space-y-3">
            <studio:checkbox name="theme_role_admin" checked>
                <span class="block font-medium text-studio-fg">{{ __('dashboard.Administrator') }}</span>
                <span class="block text-xs text-studio-muted">{{ __('dashboard.Administrator users can perform any action.') }}</span>
            </studio:checkbox>
            <studio:checkbox name="theme_role_editor">
                <span class="block font-medium text-studio-fg">{{ __('dashboard.Editor') }}</span>
                <span class="block text-xs text-studio-muted">{{ __('dashboard.Editor users have the ability to read, create, and update.') }}</span>
            </studio:checkbox>
            <studio:checkbox name="theme_role_viewer">
                <span class="block font-medium text-studio-fg">{{ __('dashboard.Viewer') }}</span>
                <span class="block text-xs text-studio-muted">{{ __('dashboard.Viewer users only have the ability to read.') }}</span>
            </studio:checkbox>
        </div>
    </studio:card>

    <studio:card>
        <p class="mb-1 text-sm font-semibold text-studio-fg">{{ __('dashboard.Communication emails') }}</p>
        <p class="mb-4 text-xs text-studio-muted">{{ __('dashboard.Receive emails about your account activity.') }}</p>
        <div class="space-y-3">
            <studio:switch name="theme_mail_activity" :label="__('dashboard.Communication emails')" checked />
            <studio:switch name="theme_mail_marketing" :label="__('dashboard.Marketing emails')" />
            <studio:switch name="theme_mail_social" :label="__('dashboard.Social emails')" />
            <studio:switch name="theme_mail_security" :label="__('dashboard.Security emails')" checked />
        </div>
    </studio:card>

    <studio:card>
        <p class="mb-1 text-sm font-semibold text-studio-fg">{{ __('dashboard.Shipping') }}</p>
        <p class="mb-4 text-xs text-studio-muted">{{ __('dashboard.Select') }}</p>
        <studio:select name="theme_shipping" placeholder="{{ __('dashboard.Choose…') }}">
            <studio:select.option value="standard" selected>{{ __('dashboard.Standard · 4–10 business days') }}</studio:select.option>
            <studio:select.option value="fast">{{ __('dashboard.Fast · 2–5 business days') }}</studio:select.option>
            <studio:select.option value="next">{{ __('dashboard.Next day · 1 business day') }}</studio:select.option>
        </studio:select>
    </studio:card>

    <studio:card>
        <div class="mb-4 flex items-center gap-3">
            <studio:avatar name="Acme Inc" />
            <div>
                <p class="text-sm font-semibold text-studio-fg">{{ __('dashboard.Acme Inc.') }}</p>
                <p class="text-xs text-studio-muted">{{ __('dashboard.Theming Supreme') }}</p>
            </div>
        </div>
        <studio:tabs selected="profile">
            <studio:tabs.list class="mb-3 rounded-xl bg-studio-soft p-1">
                <studio:tabs.tab name="profile">{{ __('dashboard.Profile') }}</studio:tabs.tab>
                <studio:tabs.tab name="user">{{ __('dashboard.User') }}</studio:tabs.tab>
                <studio:tabs.tab name="billing">{{ __('dashboard.Billing') }}</studio:tabs.tab>
            </studio:tabs.list>
            <studio:tabs.panel name="profile">
                <p class="text-sm text-studio-muted">
                    {{ __('dashboard.Live preview of Supreme components with your palette.') }}
                </p>
            </studio:tabs.panel>
            <studio:tabs.panel name="user">
                <p class="text-sm text-studio-muted">{{ __('dashboard.User settings go here.') }}</p>
            </studio:tabs.panel>
            <studio:tabs.panel name="billing">
                <p class="text-sm text-studio-muted">{{ __('dashboard.Billing details go here.') }}</p>
            </studio:tabs.panel>
        </studio:tabs>
        <div class="mt-4">
            <studio:button variant="danger" size="sm">{{ __('dashboard.Shut down') }}</studio:button>
        </div>
    </studio:card>

    <studio:card class="sm:col-span-2 xl:col-span-3">
        <p class="mb-4 text-sm font-medium text-studio-muted">{{ __('dashboard.More Supreme') }}</p>
        <div class="flex flex-wrap items-center gap-3">
            <studio:badge>{{ __('dashboard.Default') }}</studio:badge>
            <studio:badge color="teal">{{ __('dashboard.Teal') }}</studio:badge>
            <studio:badge color="amber">{{ __('dashboard.Amber') }}</studio:badge>
            <studio:badge color="red">{{ __('dashboard.Red') }}</studio:badge>
            <studio:progress :value="62" class="max-w-xs" />
            <studio:input name="theme_search" placeholder="{{ __('dashboard.Search…') }}" class="max-w-xs" />
            <studio:button variant="subtle" size="sm">
                <studio:icon name="bell" class="size-4" />
                {{ __('dashboard.Notify') }}
            </studio:button>
        </div>
        <div class="mt-4">
            <studio:callout>
                <studio:callout.heading>{{ __('dashboard.Theme preview') }}</studio:callout.heading>
                <studio:callout.text>
                    {!! __('dashboard.Base remaps <code class="text-xs">neutral</code>; accent drives primary actions and switches.') !!}
                </studio:callout.text>
            </studio:callout>
        </div>
    </studio:card>
</div>

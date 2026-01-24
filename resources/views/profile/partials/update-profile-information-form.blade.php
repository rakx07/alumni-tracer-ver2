<section>
    <header>
        <h2 class="text-lg font-semibold" style="color:#0B3D2E;">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your name and email address. Your display name is generated from your name fields.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name Fields --}}
        <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
            <div class="text-sm font-semibold text-gray-800">Full Name</div>
            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <x-input-label for="last_name" value="Last Name" />
                    <x-text-input id="last_name"
                                  name="last_name"
                                  type="text"
                                  class="mt-1 block w-full"
                                  :value="old('last_name', $user->last_name)"
                                  required
                                  autocomplete="family-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                <div>
                    <x-input-label for="first_name" value="First Name" />
                    <x-text-input id="first_name"
                                  name="first_name"
                                  type="text"
                                  class="mt-1 block w-full"
                                  :value="old('first_name', $user->first_name)"
                                  required
                                  autocomplete="given-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <div>
                    <x-input-label for="middle_name" value="Middle Name (Optional)" />
                    <x-text-input id="middle_name"
                                  name="middle_name"
                                  type="text"
                                  class="mt-1 block w-full"
                                  :value="old('middle_name', $user->middle_name)"
                                  autocomplete="additional-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
                </div>

            </div>

            {{-- Display Name Preview --}}
            <div class="mt-4 rounded-lg p-4"
                 style="background:#FFFBF0; border:1px solid #E3C77A;">
                <div class="text-xs font-semibold" style="color:#0B3D2E;">Display Name</div>
                <div class="text-sm text-gray-800 mt-1">
                    {{ $user->full_name ?? $user->name }}
                </div>
                <div class="text-xs text-gray-600 mt-1">
                    This is the name shown on your dashboard and records.
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
            <div class="text-sm font-semibold text-gray-800">Email Address</div>

            <div class="mt-3">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email"
                              name="email"
                              type="email"
                              class="mt-1 block w-full"
                              :value="old('email', $user->email)"
                              required
                              autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 rounded-lg p-4 bg-yellow-50 border border-yellow-200">
                        <p class="text-sm text-yellow-900">
                            Your email address is unverified.
                            <button form="send-verification"
                                    class="underline font-semibold text-sm text-yellow-900 hover:text-yellow-800">
                                Click here to re-send the verification email.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-700">
                                A new verification link has been sent to your email address.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center px-5 py-2 rounded font-semibold text-white"
                    style="background:#0B3D2E;">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>

<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { isSupportedLocale } from '@/i18n';
import { store as bookingStore } from '@/routes/booking';

defineProps<{
    cabinId?: number | string | null;
    authUser?: { name: string; email: string } | null;
}>();

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);
</script>

<template>
    <Head :title="$t('public.booking.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.booking.heading') }}
        </h1>
        <Form
            v-bind="bookingStore.form({ locale })"
            v-slot="{ errors, processing }"
            class="mt-6 max-w-xl space-y-4"
        >
            <div v-if="cabinId" class="text-body-sm text-body">
                {{ $t('booking.cabin') }}: {{ cabinId }}
            </div>
            <div class="grid gap-2">
                <label for="cabin_id">{{ $t('booking.cabin') }}</label>
                <input
                    id="cabin_id"
                    type="number"
                    name="cabin_id"
                    min="1"
                    :value="cabinId ?? ''"
                />
                <InputError :message="errors.cabin_id" />
            </div>
            <div class="grid gap-2">
                <label for="check_in">{{ $t('booking.checkIn') }}</label>
                <input id="check_in" type="date" name="check_in" />
                <InputError :message="errors.check_in" />
            </div>
            <div class="grid gap-2">
                <label for="check_out">{{ $t('booking.checkOut') }}</label>
                <input id="check_out" type="date" name="check_out" />
                <InputError :message="errors.check_out" />
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="grid gap-2">
                    <label for="adults">{{ $t('booking.guests') }}</label>
                    <input
                        id="adults"
                        type="number"
                        name="adults"
                        min="1"
                        value="1"
                    />
                    <InputError :message="errors.adults" />
                </div>
                <div class="grid gap-2">
                    <label for="children">{{ $t('booking.children') }}</label>
                    <input
                        id="children"
                        type="number"
                        name="children"
                        min="0"
                        value="0"
                    />
                    <InputError :message="errors.children" />
                </div>
                <div class="grid gap-2">
                    <label for="infants">{{ $t('booking.infants') }}</label>
                    <input
                        id="infants"
                        type="number"
                        name="infants"
                        min="0"
                        value="0"
                    />
                    <InputError :message="errors.infants" />
                </div>
            </div>
            <fieldset class="mt-6 max-w-xl space-y-4">
                <legend>{{ $t('public.booking.guestHeading') }}</legend>
                <div class="grid gap-2">
                    <label for="guest_name">{{
                        $t('public.booking.guestName')
                    }}</label>
                    <input
                        id="guest_name"
                        type="text"
                        name="guest_name"
                        :value="authUser?.name ?? ''"
                    />
                    <InputError :message="errors.guest_name" />
                </div>
                <div class="grid gap-2">
                    <label for="guest_email">{{
                        $t('public.booking.guestEmail')
                    }}</label>
                    <input
                        id="guest_email"
                        type="email"
                        name="guest_email"
                        :value="authUser?.email ?? ''"
                    />
                    <InputError :message="errors.guest_email" />
                </div>
                <div class="grid gap-2">
                    <label for="guest_phone">{{
                        $t('public.booking.guestPhone')
                    }}</label>
                    <input
                        id="guest_phone"
                        type="tel"
                        name="guest_phone"
                        value=""
                    />
                    <InputError :message="errors.guest_phone" />
                </div>
            </fieldset>
            <button type="submit" :disabled="processing">
                {{ $t('common.bookNow') }}
            </button>
        </Form>
    </main>
</template>

<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { isSupportedLocale } from '@/i18n';
import { store as bookingStore } from '@/routes/booking';

defineProps<{
    cabinId?: number | string | null;
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
                    <label for="children">Children</label>
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
                    <label for="infants">Infants</label>
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
            <button type="submit" :disabled="processing">
                {{ $t('common.bookNow') }}
            </button>
        </Form>
    </main>
</template>

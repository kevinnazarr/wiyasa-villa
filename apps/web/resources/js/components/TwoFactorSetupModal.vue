<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import { Check, Copy, ScanLine } from '@lucide/vue';
import { computed, nextTick, ref, useTemplateRef, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import { useAppearance } from '@/composables/useAppearance';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { confirm } from '@/routes/two-factor';
import type { TwoFactorConfigContent } from '@/types';

type Props = {
    requiresConfirmation: boolean;
    twoFactorEnabled: boolean;
};

const { resolvedAppearance } = useAppearance();

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const { copy, copied } = useClipboard();
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } =
    useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<string>('');

const pinInputContainerRef = useTemplateRef('pinInputContainerRef');

const modalConfig = computed<TwoFactorConfigContent>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: 'Two-factor authentication enabled',
            description:
                'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
            buttonText: 'Close',
        };
    }

    if (showVerificationStep.value) {
        return {
            title: 'Verify authentication code',
            description: 'Enter the 6-digit code from your authenticator app',
            buttonText: 'Continue',
        };
    }

    return {
        title: 'Enable two-factor authentication',
        description:
            'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
        buttonText: 'Continue',
    };
});

const handleModalNextStep = () => {
    if (props.requiresConfirmation) {
        showVerificationStep.value = true;

        nextTick(() => {
            pinInputContainerRef.value?.querySelector('input')?.focus();
        });

        return;
    }

    clearSetupData();
    isOpen.value = false;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData();
    }

    showVerificationStep.value = false;
    code.value = '';
};

watch(
    () => isOpen.value,
    async (isOpen) => {
        if (!isOpen) {
            resetModalState();

            return;
        }

        if (!qrCodeSvg.value) {
            await fetchSetupData();
        }
    },
);
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="flex items-center justify-center">
                <div
                    class="mb-3 border-border bg-card p-0.5 shadow-sm w-auto rounded-full border"
                >
                    <div
                        class="border-border bg-muted p-2.5 relative overflow-hidden rounded-full border"
                    >
                        <div
                            class="inset-0 absolute grid grid-cols-5 opacity-50"
                        >
                            <div
                                v-for="i in 5"
                                :key="`col-${i}`"
                                class="border-border border-r last:border-r-0"
                            />
                        </div>
                        <div
                            class="inset-0 absolute grid grid-rows-5 opacity-50"
                        >
                            <div
                                v-for="i in 5"
                                :key="`row-${i}`"
                                class="border-border border-b last:border-b-0"
                            />
                        </div>
                        <ScanLine
                            class="size-6 text-foreground relative z-20"
                        />
                    </div>
                </div>
                <DialogTitle>{{ modalConfig.title }}</DialogTitle>
                <DialogDescription class="text-center">
                    {{ modalConfig.description }}
                </DialogDescription>
            </DialogHeader>

            <div
                class="space-y-5 relative flex w-auto flex-col items-center justify-center"
            >
                <template v-if="!showVerificationStep">
                    <AlertError v-if="errors?.length" :errors="errors" />
                    <template v-else>
                        <div
                            class="max-w-md relative mx-auto flex items-center overflow-hidden"
                        >
                            <div
                                class="w-64 rounded-lg border-border relative mx-auto aspect-square overflow-hidden border"
                            >
                                <div
                                    v-if="!qrCodeSvg"
                                    class="inset-0 animate-pulse bg-background absolute z-10 flex aspect-square h-auto w-full items-center justify-center"
                                >
                                    <Spinner class="size-6" />
                                </div>
                                <div
                                    v-else
                                    class="p-5 relative z-10 overflow-hidden border"
                                >
                                    <div
                                        v-html="qrCodeSvg"
                                        class="flex aspect-square size-full items-center justify-center"
                                        :style="{
                                            filter:
                                                resolvedAppearance === 'dark'
                                                    ? 'invert(1) brightness(1.5)'
                                                    : undefined,
                                        }"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="space-x-5 flex w-full items-center">
                            <Button class="w-full" @click="handleModalNextStep">
                                {{ modalConfig.buttonText }}
                            </Button>
                        </div>

                        <div
                            class="relative flex w-full items-center justify-center"
                        >
                            <div
                                class="inset-0 bg-border absolute top-1/2 h-px w-full"
                            />
                            <span class="bg-card px-2 py-1 relative"
                                >or, enter the code manually</span
                            >
                        </div>

                        <div
                            class="space-x-2 flex w-full items-center justify-center"
                        >
                            <div
                                class="rounded-xl border-border flex w-full items-stretch overflow-hidden border"
                            >
                                <div
                                    v-if="!manualSetupKey"
                                    class="bg-muted p-3 flex h-full w-full items-center justify-center"
                                >
                                    <Spinner />
                                </div>
                                <template v-else>
                                    <input
                                        type="text"
                                        readonly
                                        :value="manualSetupKey"
                                        class="bg-background p-3 text-foreground h-full w-full"
                                    />
                                    <button
                                        @click="copy(manualSetupKey || '')"
                                        class="border-border px-3 hover:bg-muted relative block h-auto border-l"
                                    >
                                        <Check
                                            v-if="copied"
                                            class="w-4 text-green-500"
                                        />
                                        <Copy v-else class="w-4" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>

                <template v-else>
                    <Form
                        v-bind="confirm.form()"
                        error-bag="confirmTwoFactorAuthentication"
                        reset-on-error
                        @finish="code = ''"
                        @success="isOpen = false"
                        v-slot="{ errors, processing }"
                    >
                        <input type="hidden" name="code" :value="code" />
                        <div
                            ref="pinInputContainerRef"
                            class="space-y-3 relative w-full"
                        >
                            <div
                                class="space-y-3 py-2 flex w-full flex-col items-center justify-center"
                            >
                                <InputOTP
                                    id="otp"
                                    v-model="code"
                                    :maxlength="6"
                                    :disabled="processing"
                                    autofocus
                                >
                                    <InputOTPGroup>
                                        <InputOTPSlot
                                            v-for="index in 6"
                                            :key="index"
                                            :index="index - 1"
                                        />
                                    </InputOTPGroup>
                                </InputOTP>
                                <InputError :message="errors?.code" />
                            </div>

                            <div class="space-x-5 flex w-full items-center">
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="w-auto flex-1"
                                    @click="showVerificationStep = false"
                                    :disabled="processing"
                                >
                                    Back
                                </Button>
                                <Button
                                    type="submit"
                                    class="w-auto flex-1"
                                    :disabled="processing || code.length < 6"
                                >
                                    Confirm
                                </Button>
                            </div>
                        </div>
                    </Form>
                </template>
            </div>
        </DialogContent>
    </Dialog>
</template>

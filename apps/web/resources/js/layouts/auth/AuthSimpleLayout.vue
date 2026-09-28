<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="gap-6 bg-background p-6 md:p-10 flex min-h-svh flex-col items-center justify-center"
    >
        <div class="max-w-sm w-full">
            <div class="gap-8 flex flex-col">
                <div class="gap-4 flex flex-col items-center">
                    <Link
                        :href="home()"
                        class="gap-2 font-medium flex flex-col items-center"
                    >
                        <div
                            class="mb-1 h-9 w-9 rounded-md flex items-center justify-center"
                        >
                            <AppLogoIcon
                                class="size-9 dark:text-white fill-current text-[var(--foreground)]"
                            />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-sm text-muted-foreground text-center">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

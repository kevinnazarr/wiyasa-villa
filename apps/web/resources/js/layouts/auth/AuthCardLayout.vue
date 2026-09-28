<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="gap-6 bg-muted p-6 md:p-10 flex min-h-svh flex-col items-center justify-center"
    >
        <div class="max-w-md gap-6 flex w-full flex-col">
            <Link
                :href="home()"
                class="gap-2 font-medium flex items-center self-center"
            >
                <div class="h-9 w-9 flex items-center justify-center">
                    <AppLogoIcon
                        class="size-9 text-black dark:text-white fill-current"
                    />
                </div>
            </Link>

            <div class="gap-6 flex flex-col">
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-xl">{{ title }}</CardTitle>
                        <CardDescription>
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

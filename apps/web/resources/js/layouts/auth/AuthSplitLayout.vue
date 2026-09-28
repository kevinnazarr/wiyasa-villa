<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 relative grid h-dvh flex-col items-center justify-center"
    >
        <div
            class="bg-muted p-10 text-white lg:flex relative hidden h-full flex-col dark:border-r"
        >
            <div class="inset-0 bg-zinc-900 absolute" />
            <Link
                :href="home()"
                class="text-lg font-medium relative z-20 flex items-center"
            >
                <AppLogoIcon class="mr-2 size-8 text-white fill-current" />
                {{ name }}
            </Link>
        </div>
        <div class="lg:p-8">
            <div
                class="space-y-6 sm:w-[350px] mx-auto flex w-full flex-col justify-center"
            >
                <div class="space-y-2 flex flex-col text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

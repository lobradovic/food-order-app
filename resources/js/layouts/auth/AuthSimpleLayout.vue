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
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 auth-page">
        <div class="w-full max-w-sm auth-container">
            <div class="flex flex-col gap-8 auth-header">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="home()" class="flex flex-col items-center gap-2 font-medium auth-logo-link">
                        <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md auth-logo">
                            <img src="images/logo-black.png" alt="Logo" class="auth-logo-icon" />
                        </div>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium auth-title">{{ title }}</h1>
                        <p class="text-muted-foreground text-center text-sm auth-description">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

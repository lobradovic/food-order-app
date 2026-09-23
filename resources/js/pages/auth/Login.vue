<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import PasskeyVerify from '@/components/PasskeyVerify.vue';

defineOptions({
    layout: {
        title: 'Prijava',
        description: 'Unesite vašu e-mail adresu i lozinku u polja ispod',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>

    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600 login-status">
        {{ status }}
    </div>

    <!--<PasskeyVerify />-->

    <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }"
        class="flex flex-col gap-6 login-form">
        <div class="grid gap-6 login-fields">
            <div class="grid gap-2 login-field">
                <Label for="email" class="login-label">E-mail adresa</Label>
                <Input id="email" type="email" name="email" required autofocus :tabindex="1" autocomplete="email"
                    placeholder="email@example.com" class="login-input"/>
                <InputError :message="errors.email" class="login-error"/>
            </div>

            <div class="grid gap-2 login-field">
                <div class="flex items-center justify-between login-password-header">
                    <Label for="password" class="login-label">Lozinka</Label>
                    <TextLink v-if="canResetPassword" :href="request()" class="text-sm login-forgot" :tabindex="5">
                        Zaboravili ste lozinku?
                    </TextLink>
                </div>
                <PasswordInput id="password" name="password" required :tabindex="2" autocomplete="current-password"
                    placeholder="Lozinka" class=" login-input" />
                <InputError :message="errors.password" class="login-error"/>
            </div>

            <div class="flex items-center justify-between login-remember">
                <Label for="remember" class="flex items-center space-x-3 login-remember-label">
                    <input type="checkbox" id="remember" name="remember" :tabindex="3" />
                    <span>Zapamti me</span>
                </Label>
            </div>

            <Button type="submit" class="mt-4 w-full login-button" :tabindex="4" :disabled="processing" data-test="login-button" >
                <Spinner v-if="processing" />
                Prijava
            </Button>
        </div>

        <div class="text-muted-foreground text-center text-sm login-register">
            Nemate nalog?
            <TextLink :href="register()" :tabindex="5" class="login-register-link">Registrujte se</TextLink>
        </div>
    </Form>
</template>

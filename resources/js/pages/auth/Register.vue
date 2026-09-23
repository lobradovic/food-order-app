<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Kreirajte nalog',
        description: 'Unesite potrebne podatke u polja ispod za kreiranje naloga',
    },
});
</script>

<template>

    <Head title="Register" />

    <Form v-bind="store.form()" :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }" class="flex flex-col gap-6 auth-form login-form">
        <div class="grid gap-6 login-fields">
            <div class="grid gap-2 login-field">
                <Label for="name" class="login-label">Ime</Label>
                <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" name="name"
                    placeholder="Puno ime" class="login-input"/>
                <InputError :message="errors.name" class="login-error"/>
            </div>

            <div class="grid gap-2 login-field">
                <Label for="email" class="login-label">E-mail adresa</Label>
                <Input id="email" type="email" required :tabindex="2" autocomplete="email" name="email"
                    placeholder="email@example.com" class="login-input"/>
                <InputError :message="errors.email" class="login-error"/>
            </div>

            <div class="grid gap-2 login-field">
                <Label for="password" class="login-label">Lozinka</Label>
                <PasswordInput id="password" required :tabindex="3" autocomplete="new-password" name="password"
                    placeholder="Lozinka" :passwordrules="passwordRules" class="login-input"/>
                <InputError :message="errors.password" class="login-error"/>
            </div>

            <div class="grid gap-2 login-field">
                <Label for="password_confirmation" class="login-label">Potvrdite lozinku</Label>
                <PasswordInput id="password_confirmation" required :tabindex="4" autocomplete="new-password"
                    name="password_confirmation" placeholder="Potvrdite lozinku" :passwordrules="passwordRules" class="login-input"/>
                <InputError :message="errors.password_confirmation" class="login-error"/>
            </div>

            <Button type="submit" class="mt-2 w-full login-button" tabindex="5" :disabled="processing"
                data-test="register-user-button">
                <Spinner v-if="processing" />
                Registracija
            </Button>
        </div>

        <div class="text-muted-foreground text-center text-sm login-register">
            Već posedujete nalog?
            <TextLink :href="login()" class="underline underline-offset-4" :tabindex="6">Prijavite se</TextLink>
        </div>
    </Form>
</template>

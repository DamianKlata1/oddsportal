<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import apiPublic from '/assets/api/apiPublic.js'
import Toast from '/assets/tools/Toast.js'
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useI18n } from 'vue-i18n';
import { PASSWORD_MIN_LENGTH, PASSWORD_MAX_LENGTH } from '/assets/config/validationRules.js';

const { t } = useI18n();
const route = useRoute()
const token = route.query.token

const loader = ref(false)
const router = useRouter()

const schema = yup.object({
    password: yup
        .string(t('password_must_be_a_string'))
        .required(t('password_required'))
        .min(PASSWORD_MIN_LENGTH, t('password_too_short', { min: PASSWORD_MIN_LENGTH }))
        .max(PASSWORD_MAX_LENGTH, t('password_too_long', { max: PASSWORD_MAX_LENGTH })),
    passwordConfirmation: yup
        .string(t('password_confirmation_must_be_a_string'))
        .required(t('password_confirmation_required'))
        .oneOf([yup.ref('password')], t('passwords_do_not_match')),
});


function submit(values) {
    loader.value = true
    const payload = {
        password: values.password,
        token: token
    }
    apiPublic().post('/api/reset-password/reset', payload)
        .then((response) => {
            Toast(response.data.message, 'success')
            router.push({ name: 'login' })
        })
        .catch((error) => {
            if (error.response?.data?.detail) {
                Toast(error.response.data.detail, 'error')
            } else if (error.response?.data?.message) {
                Toast(error.response.data.message, 'error')
            }
        })
        .finally(() => {
            loader.value = false
        })
}
</script>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">{{ $t('reset_password') }}</h3>
                    </div>
                    <div class="card-body">
                        <Form @submit="submit" :validation-schema="schema" method="post">
                            <div class="form-floating mb-3">
                                <Field class="form-control" id="inputPassword" type="password" name="password" />
                                <label for="inputPassword">{{ $t('password') }}</label>
                                <ErrorMessage name="password" class="text-danger" />
                            </div>
                            <div class="form-floating mb-3">
                                <Field class="form-control" id="inputConfirmPassword" type="password" name="passwordConfirmation" />
                                <label for="inputConfirmPassword">{{ $t('confirm_password') }}</label>
                                <ErrorMessage name="passwordConfirmation" class="text-danger" />
                            </div>
                            <div class="card-footer text-center py-3">
                                <button v-show="!loader" class="btn btn-primary" type="submit">
                                    {{ $t('reset_password') }}
                                </button>
                                <button v-show="loader" class="btn btn-primary" type="submit" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    <span class="ms-1">{{ $t('loading') }}</span>
                                </button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

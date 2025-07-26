<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import apiPublic from '/assets/api/apiPublic.js'
import Toast from '/assets/tools/Toast.js'
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const schema = yup.object({
    email: yup
        .string(t('email_must_be_a_string'))
        .required(t('email_required'))
        .email(t('email_invalid')),
});

const loader = ref(false)
const router = useRouter()

function submit(values) {
    loader.value = true
    apiPublic().post('/api/reset-password', values)
        .then((response) => {
            Toast(response.data.message, 'success')
            router.push({ name: 'login' })
        })
        .catch((error) => {
            console.log(error)
            if (error.response?.data?.error) {
                Toast(error.response.data.error, 'error')
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
                        <h3 class="text-center font-weight-light my-4">{{ $t('change_password') }}</h3>
                    </div>
                    <div class="card-body">
                        <Form @submit="submit" :validation-schema="schema" method="post">
                            <div class="form-floating mb-3">
                                <Field class="form-control" id="inputEmail" type="email" name="email" />
                                <label for="inputEmail">{{ $t('email_address') }}</label>
                                <ErrorMessage name="email" class="text-danger" />
                            </div>
                            <div class="card-footer text-center py-3">
                                <button v-show="!loader" class="btn btn-primary" type="submit">
                                    {{ $t('change_password') }}
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

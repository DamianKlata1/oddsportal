<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { RouterLink } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { PASSWORD_MIN_LENGTH, PASSWORD_MAX_LENGTH } from '/assets/config/validationRules.js';
const { t } = useI18n();

const schema = yup.object({
    email: yup
        .string(t('email_must_be_a_string'))
        .required(t('email_required'))
        .email(t('email_invalid')),
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
</script>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">{{ $t('create_account') }}</h3>
                    </div>
                    <div class="card-body">
                        <Form @submit="submit" :validation-schema="schema" method="post">
                            <div class="form-floating mb-3">
                                <Field class="form-control" id="inputEmail" type="email" name="email" />
                                <label for="inputEmail">{{ $t('email_address') }}</label>
                                <ErrorMessage name="email" class="text-danger" />
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <Field class="form-control" id="inputPassword" name="password"
                                            type="password" />
                                        <label for="inputPassword">{{ $t('password') }}</label>
                                        <ErrorMessage name="password" class="text-danger" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <Field class="form-control" id="passwordConfirmation"
                                            name="passwordConfirmation" type="password" />
                                        <label for="passwordConfirmation">{{ $t('confirm password') }}</label>
                                        <ErrorMessage name="passwordConfirmation" class="text-danger" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 mb-0">
                                <div class="d-grid">
                                    <button v-show="!loader" class="btn btn-primary btn-block" type="submit">{{
                                        $t('create_account') }}</button>
                                    <button v-show="loader" class="btn btn-primary btn-block" type="submit" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ms-1">{{ $t('loading') }}</span>
                                    </button>
                                </div>
                            </div>
                        </Form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">
                            <RouterLink to="/login">{{ $t('have_an_account') }}</RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import apiPublic from '/assets/api/apiPublic.js';
import Toast from '/assets/tools/Toast.js';
export default {
    data() {
        let loader = false;
        return {
            loader
        }
    },
    methods: {
        submit(values) {
            this.loader = true;
            apiPublic().post('/api/register', values)
                .then((response) => {
                    Toast(response.data.message, 'success');
                    this.$router.push({ name: 'login' });
                })
                .catch(function (error) {
                    if (error.response.data.detail) {
                        Toast(error.response.data.detail, 'error');
                    } else if (error.response.data.message) {
                        Toast(error.response.data.message, 'error');
                    }
                })
                .finally(() => this.loader = false);
        }
    }
}
</script>
<style></style>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const user = usePage().props.auth.user;

const form = useForm({
  name: user.name,
  email: user.email,
  last_name: user.last_name,
  phone: user.phone,
  current_password: '',
  new_password: '',
  repeat_new_password: '',
});
</script>

<template>
  <form @submit.prevent="form.patch('/profile', { preserveScroll: true })">
    <div
      v-if="
        mustVerifyEmail &&
        user.email_verified_at === null &&
        status === 'verification-link-sent'
      "
      class="alert alert-success d-flex align-items-center justify-content-center fs-sm fw-medium mb-4"
      role="alert"
    >
      <i class="fa fa-check-circle me-2 opacity-50 flex-shrink-0"></i>
      <span>
        A new verification link has been sent to your email address.
      </span>
    </div>
    <!-- <div class="mb-4">
      <label class="form-label">Avatar (from Gravatar)</label>
      <div>
        <img
          class="img-avatar img-avatar-thumb"
          :src="$page.props.auth.user.gravatar"
          alt="User Avatar"
        />
      </div>
    </div> -->
    <div class="mb-4">
      <label class="form-label" for="name">Nombre</label>
      <input
        id="name"
        type="text"
        class="form-control form-control-lg form-control-alt"
        :class="{
          'is-invalid': form.errors.name,
        }"
        v-model="form.name"
        required
        autocomplete="name"
      />
      <div v-show="form.errors.name" class="invalid-feedback">
        {{ form.errors.name }}
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label" for="name">Apellido</label>
      <input
        id="name"
        type="text"
        class="form-control form-control-lg form-control-alt"
        :class="{
          'is-invalid': form.errors.last_name,
        }"
        v-model="form.last_name"
        required
        autocomplete="name"
      />
      <div v-show="form.errors.last_name" class="invalid-feedback">
        {{ form.errors.last_name }}
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label" for="email">Email</label>
      <input
        id="email"
        type="email"
        class="form-control form-control-lg form-control-alt"
        :class="{
          'is-invalid': form.errors.email,
        }"
        v-model="form.email"
        required
        autocomplete="username"
      />
      <div v-show="form.errors.email" class="invalid-feedback">
        {{ form.errors.email }}
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label" for="name">Telefono</label>
      <input
        id="name"
        type="text"
        class="form-control form-control-lg form-control-alt"
        :class="{
          'is-invalid': form.errors.phone,
        }"
        v-model="form.phone"
        required
        autocomplete="phone"
      />
      <div v-show="form.errors.phone" class="invalid-feedback">
        {{ form.errors.phone }}
      </div>
    </div>

    <div class="d-flex align-items-center gap-2">
      <Link
        v-if="mustVerifyEmail && user.email_verified_at === null"
        href="/email/verification-notification"
        method="post"
        as="button"
        class="btn btn-alt-primary"
      >
        Re-send the verification email
      </Link>
      <button type="submit" class="btn btn-primary" :disabled="form.processing">
        Guardar
      </button>
      <div v-if="form.recentlySuccessful" class="fs-sm text-muted">Guardado!</div>
    </div>
  </form>
</template>

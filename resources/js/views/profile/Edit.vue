<script setup>
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});
</script>

<template>
  <Head title="Profile" />

  <!-- Hero -->
  <BaseBackground image="/assets/media/photos/photo33@2x.jpg">
    <div class="bg-primary-dark-op">
      <div class="content content-full text-center">
        <div class="my-3">
          <img
            class="img-avatar img-avatar-thumb"
            :src="$page.props.auth.user.gravatar"
            alt="User Avatar"
          />
        </div>
        <h1 class="h2 text-white mb-0">{{ $page.props.auth.user.name }}</h1>
        <h2 class="h4 fw-normal text-white-75">Actualice sus datos</h2>
      </div>
    </div>
  </BaseBackground>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content content-boxed">
    <div class="alert alert-warning alert-dismissible" role="alert" v-if="$page.props.auth.user.has_default_password">
      <h3 class="alert-heading h4 my-2">Cambie su contraseña</h3>
      <p class="mb-0">
        Su contraseña fue restablecida por el administrador del sistema,
        es necesario que <strong>cambie su contraseña</strong> en la sección inferior
      </p>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <BaseBlock title="Informacion del usuario">
      <div class="row push">
        <div class="col-lg-4">
          <p class="fs-sm text-muted">
            Datos Genetales
          </p>
        </div>
        <div class="col-lg-8 col-xl-5">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
          />
        </div>
      </div>
    </BaseBlock>

    <BaseBlock title="Cambiar Contraseña">
      <div class="row push">
        <div class="col-lg-4">
          <p class="fs-sm text-muted">
            La contraseña debe tener al menos 8 caracteres alfanumericos
          </p>
        </div>
        <div class="col-lg-8 col-xl-5">
          <UpdatePasswordForm />
        </div>
      </div>
    </BaseBlock>

  </div>
  <!-- END Page Content -->
</template>

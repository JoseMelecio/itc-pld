<script setup>

import {usePage} from "@inertiajs/vue3";

const page = usePage();
const showSuspendedAlert = page.props.auth?.user?.status === "suspended"
</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Dashboard"
    :subtitle="`Generador de notificaciones PLD`"
  >
    <template #extra>
    </template>
  </BasePageHeading>

  <div class="content">

    <div class="alert alert-info alert-dismissible" role="alert" v-if=" page.props.auth?.user?.erase_cache">
      <h3 class="alert-heading h4 my-2">Borra las cookies!</h3>
      <p class="mb-0">
        El sistema se actualizo, es necesario que elimines las cookies y la cache!
        <br><br>
        <a href="/users-erase-cache-done" class="btn btn-info">Entendido </a>
      </p>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row items-push">
      <div class="col-sm-6 col-xl-4">
<!--        <BaseBlock title="Title" class="h-100 mb-0" content-class="fs-sm">-->
<!--          <p>Content..</p>-->
<!--        </BaseBlock>-->

        <div class="alert alert-warning alert-dismissible" role="alert" v-if="showSuspendedAlert">
          <h3 class="alert-heading h4 my-2">Suspendido</h3>
          <p class="mb-0">
            Usuario suspendido!
          </p>

        </div>
      </div>
    </div>
  </div>
</template>

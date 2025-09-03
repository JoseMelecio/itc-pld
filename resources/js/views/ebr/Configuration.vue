<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  errors: Object,
  configs: {
    type: Object,
    default: () => ({
      template_clients_config: null,
      template_operations_config: null,
    }),
  }
})

const form = useForm({
  ...props.configs,
});

function submit() {
  form.post('/ebr-configuration', {
    onSuccess: () => {
      form.reset();
    }
  })
}


</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="EBR"
    :subtitle="`Configuracion del EBR`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Configuracion EBR
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">

    <div class="alert alert-danger alert-dismissible" role="alert" v-if="hasFormErrors">
      <p class="mb-0" v-for="error in formErrors">
        {{ error }}
      </p>
      <button type="button" class="btn-close" @click="hasFormErrors = false"></button>
    </div>


    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit()" enctype="multipart/form-data">
          <BaseBlock title="Configuracion de las plantillas" class="h-100 mb-0" content-class="fs-sm">

            <div class="row">
              <div class="col-6">
                <div class="mb-4">
                  <label class="form-label" for="file">Plantilla Clientes <span class="text-danger">*</span></label>
                  <textarea
                    v-model="form.template_clients_config"
                    class="form-control"
                    :class="{ 'is-invalid': errors.template_clients_config }"
                    id="template_clients_config"
                    name="template_clients_config"
                    rows="10"
                    ></textarea>
                  <div id="template_clients_config-error" class="text-danger">{{ errors.template_clients_config}}</div>
                </div>
              </div>

              <div class="col-6">
                <div class="mb-4">
                  <label class="form-label" for="file">Plantilla Operaciones <span class="text-danger">*</span></label>
                  <textarea
                    v-model="form.template_operations_config"
                    class="form-control"
                    :class="{ 'is-invalid': errors.template_operations_config }"
                    id="template_operations_config"
                    name="template_operations_config"
                    rows="10"
                  ></textarea>
                  <div id="template_operations_config-error" class="text-danger">{{ errors.template_operations_config}}</div>
                </div>
              </div>

            </div>

            <div class="mb-4">
              <button type="submit" class="btn btn-success me-2">Guardar</button>
            </div>


          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

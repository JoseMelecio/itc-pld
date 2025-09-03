<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted, watch } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  errors: Object,
  riskElement: {
    type: Object,
    default: () => ({
      risk_element: null,
      lateral_header: null,
      sub_header: null,
      description: null,
      report_config: {},
      active: true,
    }),
  }
})

const form = useForm({
  ...props.riskElement,
});

function submit() {
  if (!form.id) {
    form.post('/ebr_inherent_risk_catalog', {
      onSuccess: () => {
        form.reset();
      }
    })
  } else {
    form.patch('/ebr_inherent_risk_catalog/' +  form.id, {
      onSuccess: () => {
        form.reset();
      }
    })
  }
}

function deleteRiskElement() {
  form.delete('/ebr_inherent_risk_catalog/' +  form.id, {
    onSuccess: () => {
      form.reset();
    }
  })
}


function toTitleCase(str) {
  if (!str) return "";
  return str
    .toLowerCase()
    .replace(/\b\w/g, char => char.toUpperCase());
}

function formatNumber(num) {
  if (num == null) return "";
  return new Intl.NumberFormat("es-MX").format(num);
}

function clearForm() {
  form.reset()
}

const reportConfigString = ref(
    JSON.stringify(form.report_config ?? {}, null, 2)
)

watch(reportConfigString, (newVal) => {
  try {
    form.report_config = JSON.parse(newVal)
  } catch (e) {
    // si el JSON no es válido, no lo pisamos (así no rompe el form)
  }
});

watch(() => form.report_config, (newVal) => {
  reportConfigString.value = JSON.stringify(newVal, null, 2)
}, { deep: true });

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Elementos de Riesgo"
    :subtitle="`Nuevo`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a class="link-fx" href="/ebr_inherent_risk_catalog">Elementos de Riesgo</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Elementos de riesgo
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
        <form @submit.prevent="submit()">
          <BaseBlock title="Nuevo Elemento de Riesgo" class="h-100 mb-0" content-class="fs-sm">
            <div class="block-content block-content-full">

              <div class="row">
                <div class="col-6">

                  <div class="mb-4">
                    <label class="form-label" for="month">Elemento de Riesgo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.risk_element }"  id="risk_element" name="risk_element" placeholder="Elemento de riesgo" v-model="form.risk_element">
                    <div id="risk_element-error" class="text-danger" >{{ errors.risk_element }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Sub Encabezado <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.sub_header }"  id="sub_header" name="sub_header" placeholder="Sub Encabezado" v-model="form.sub_header">
                    <div id="sub_header-error" class="text-danger" >{{ errors.sub_header }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Encabezado Lateral <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.lateral_header }"  id="lateral_header" name="lateral_header" placeholder="Encabezado Lateral" v-model="form.lateral_header">
                    <div id="lateral_header-error" class="text-danger" >{{ errors.lateral_header }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="file">Descripcion <span class="text-danger">*</span></label>
                    <textarea
                        v-model="form.description"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': errors.description }"
                        id="description"
                        name="description"
                        rows="4"
                    ></textarea>
                    <div id="decription-error" class="text-danger">{{ errors.decription}}</div>

                    <div class="mb-4">
                      <label class="form-label" for="example-select">Activo <span class="text-danger">*</span></label>
                      <select class="form-select form-control form-control-sm" id="active" name="active" v-model="form.active">
                        <option value="true">Si</option>
                        <option value="false">No</option>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="col-6">
                  <div class="mb-4">
                    <label class="form-label" for="file">Reglas <span class="text-danger">*</span></label>
                    <textarea
                        v-model="reportConfigString"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': errors.report_config }"
                        id="report_config"
                        name="report_config"
                        rows="19"
                    ></textarea>
                    <div id="report_config-error" class="text-danger">{{ errors.report_config}}</div>
                  </div>
                </div>

              </div>

              <hr>

              <div class="mb-4">
                <button type="submit" class="btn btn-success me-2">Guardar</button>
                <button type="button" @click="clearForm" class="btn btn-info me-2" v-if="form.id === null">Limpiar</button>
                <button type="button" @click="deleteRiskElement" class="btn btn-danger me-2" v-if="form.id">Eliminar</button>
              </div>

            </div>
          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

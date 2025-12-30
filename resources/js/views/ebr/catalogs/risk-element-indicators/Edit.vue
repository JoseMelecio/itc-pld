<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted, watch } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  riskElements: Object,
  errors: Object,
  riskIndicator: {
    type: Object,
    default: () => ({
      characteristic: null,
      key: null,
      name: null,
      description: null,
      type: null,
      report_config: {},
      sql: null,
      risk_element_id: null,
      order: null,
    }),
  }
})

const form = useForm({
  ...props.riskIndicator,
});

function submit() {
  if (!form.id) {
    form.post('/ebr_indicators_risk_catalog', {
      onSuccess: () => {
        form.reset();
      }
    })
  } else {
    form.patch('/ebr_indicators_risk_catalog/' +  form.id, {
      onSuccess: () => {
        form.reset();
      }
    })
  }
}

function deleteRiskElement() {
  form.delete('/ebr_indicators_risk_catalog/' +  form.id, {
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
    title="Indicadores de Riesgo"
    :subtitle="`Nuevo`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a class="link-fx" href="/ebr_indicators_risk_catalog">Indicadores de Riesgo</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Indicadores de riesgo
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
          <BaseBlock title="Nuevo Indicador de Riesgo" class="h-100 mb-0" content-class="fs-sm">
            <div class="block-content block-content-full">

              <div class="row">
                <div class="col-6">

                  <div class="mb-4">
                    <label class="form-label" for="month">Caracteristica <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.characteristic }"  id="characteristic" name="characteristic" placeholder="Indicador de riesgo" v-model="form.characteristic">
                    <div id="characteristic-error" class="text-danger" >{{ errors.characteristic }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Clave</label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.key }"  id="key" name="key" placeholder="Clave" v-model="form.key">
                    <div id="key-error" class="text-danger" >{{ errors.key }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.name }"  id="name" name="name" placeholder="Nombre" v-model="form.name">
                    <div id="name-error" class="text-danger" >{{ errors.name }}</div>
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
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Tipo de indicador de riesgo</label>
                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.type }"  id="type" name="type" placeholder="LD/FT" v-model="form.type">
                    <div id="type-error" class="text-danger" >{{ errors.type }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Indicador de Riesgo <span class="text-danger">*</span></label>
                    <select class="form-select" id="risk_element_id" name="risk_element_id" v-model="form.risk_element_id">
                      <option selected="">Seleccione una opcion</option>
                      <option v-for="element in riskElements" :key="element.id" :value="element.id">{{ element.risk_element }}</option>
                    </select>
                    <div id="risk_element_id-error" class="text-danger" >{{ errors.risk_element_id }}</div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="month">Orden <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': errors.order }"  id="order" name="order" placeholder="Indicador de Riesgo" v-model="form.order">
                    <div id="order-error" class="text-danger" >{{ errors.order }}</div>
                  </div>

                </div>

                <div class="col-6">
                  <div class="mb-4">
                    <label class="form-label" for="file">SQL <span class="text-danger">*</span></label>
                    <textarea
                        v-model="form.sql"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': errors.sql }"
                        id="sql"
                        name="sql"
                        rows="24"
                    ></textarea>
                    <div id="sql-error" class="text-danger">{{ errors.sql}}</div>
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

<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  riskZones: Object,
  errors: Object,
})

const form = useForm({
  id: '',
  risk_zone: '',
  incidence_of_crime: '',
  percentage_1: '',
  percentage_2: '',
  zone: '',
  color: '',
});

function submit() {
  if (form.id) {
    form.patch('/ebr-risk-zones-catalog/' + form.id, {
      onSuccess: () => {
        form.reset();
      }
    })
  } else {
    form.post('/ebr-risk-zones-catalog', {
      onSuccess: () => {
        form.reset();
      }
    })
  }
}

function deleteRiskZone() {
  form.delete('/ebr-risk-zones-catalog/' + form.id, {
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

function loadForm(riskZone) {
    form.id = riskZone.id
    form.risk_zone = riskZone.risk_zone
    form.incidence_of_crime = riskZone.incidence_of_crime
    form.percentage_1 = riskZone.percentage_1
    form.percentage_2 = riskZone.percentage_2
    form.zone = riskZone.zone
    form.color = riskZone.color
}
</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Zonas de Riesgo"
    :subtitle="`Catalogo`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Catalogo Zonas de Riesgo
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
          <BaseBlock title="Zonas de Riesgo" class="h-100 mb-0" content-class="fs-sm">
            <div class="row">

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Zona de Riesgo <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.risk_zone }"  id="risk_zone" name="risk_zone" placeholder="Zona de Riesgo" v-model="form.risk_zone">
                  <div id="risk_zone-error" class="text-danger" >{{ errors.risk_zone }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Incidencia Delictiva <span class="text-danger">*</span></label>
                  <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': errors.incidence_of_crime }"  id="incidence_of_crime" name="incidence_of_crime" placeholder="Incidencia Delictiva" v-model="form.incidence_of_crime">
                  <div id="incidence_of_crime-error" class="text-danger" >{{ errors.incidence_of_crime }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Porcentaje 1<span class="text-danger">*</span></label>
                  <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': errors.percentage_1 }"  id="percentage_1" name="percentage_1" placeholder="Porcentaje 1" v-model="form.percentage_1">
                  <div id="percentage_1-error" class="text-danger" >{{ errors.percentage_1 }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Porcentaje 2<span class="text-danger">*</span></label>
                  <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': errors.percentage_2 }"  id="percentage_2" name="percentage_2" placeholder="Porcentaje 2" v-model="form.percentage_2">
                  <div id="percentage_2-error" class="text-danger" >{{ errors.percentage_2 }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Zona<span class="text-danger">*</span></label>
                  <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': errors.zone }"  id="zone" name="zone" placeholder="Zona" v-model="form.zone">
                  <div id="zone-error" class="text-danger" >{{ errors.zone }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Color <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': errors.color }"  id="color" name="color" placeholder="Color" v-model="form.color">
                  <div id="color-error" class="text-danger" >{{ errors.color }}</div>
                </div>
              </div>

            </div>
            <div class="mb-4">
              <button type="submit" class="btn btn-success me-2">Guardar</button>
              <button type="button" @click="clearForm" class="btn btn-info me-2">Limpiar</button>
              <button type="button" @click="deleteRiskZone" class="btn btn-danger me-2" v-if="form.id">Eliminar</button>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-hover table-sm table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Zona de Riesgo</th>
                    <th class="d-none d-sm-table-cell">Incidencia Delictiva</th>
                    <th class="d-none d-sm-table-cell">Porcentaje 1</th>
                    <th class="d-none d-sm-table-cell">Porcentaje 2</th>
                    <th class="d-none d-sm-table-cell">Zona</th>
                    <th class="d-none d-sm-table-cell">Color</th>
                    <th class="d-none d-sm-table-cell"></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(riskZone, index) in riskZones" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(riskZone.risk_zone) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ formatNumber(riskZone.incidence_of_crime) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskZone.percentage_1 }}%
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskZone.percentage_2 }}%
                    </td>
                    <td class="text-center">
                      {{ riskZone.zone }}
                    </td>
                    <td class="text-center flex items-center justify-center gap-2">
                      <div
                          :style="{
                          backgroundColor: '#' + riskZone.color,
                          width: '100px',
                          height: '20px',
                          border: '1px solid #ccc'
                        }"
                      >{{ riskZone.color }}</div>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button type="button" @click="loadForm(riskZone)" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit Client" data-bs-original-title="Edit Client">
                          <i class="fa fa-fw fa-pencil-alt"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>

            <div class="block-content block-content-full">
            </div>
            </div>
          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

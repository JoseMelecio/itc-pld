<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  riskIndicators: Object,
  errors: Object,
})

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
</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Indicadores de Riesgo"
    :subtitle="`Catalogo`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Catalogo Indicadores de Riesgo
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
          <BaseBlock title="Indicadores de Riesgo" class="h-100 mb-0" content-class="fs-sm">
            <div class="mb-4">
              <a href="/ebr_indicators_risk_catalog_create" class="btn btn-success me-2">Nuevo</a>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-hover table-sm table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Caracteristica</th>
                    <th class="d-none d-sm-table-cell" style="width: 100px;" >Clave</th>
                    <th class="d-none d-sm-table-cell">Nombre</th>
                    <th class="d-none d-sm-table-cell">Descripcion</th>
                    <th class="d-none d-sm-table-cell">Tipo</th>
                    <th class="d-none d-sm-table-cell"></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(riskIndicator, index) in riskIndicators" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ riskIndicator.characteristic }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskIndicator.key }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskIndicator.name }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskIndicator.description }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskIndicator.type }}
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                          data-bs-toggle="tooltip"
                          aria-label="Editar"
                          data-bs-original-title="Editar"
                          @click="router.visit(`/ebr_indicators_risk_catalog/${riskIndicator.id}`)"
                        >
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

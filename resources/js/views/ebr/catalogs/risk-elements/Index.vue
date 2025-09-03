<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  riskElements: Object,
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
    title="Elementos de Riesgo"
    :subtitle="`Catalogo`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Catalogo Elementos de Riesgo
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
            <div class="mb-4">
              <a href="/ebr_inherent_risk_catalog_create" class="btn btn-success me-2">Nuevo</a>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-hover table-sm table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Elementos de Riesgo</th>
                    <th class="d-none d-sm-table-cell">Sub Encabezado</th>
                    <th class="d-none d-sm-table-cell">Encabezado Lateral</th>
                    <th class="d-none d-sm-table-cell">Descripcion</th>
                    <th class="d-none d-sm-table-cell">Activo</th>
                    <th class="d-none d-sm-table-cell"></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(riskElement, index) in riskElements" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(riskElement.risk_element) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(riskElement.sub_header) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskElement.lateral_header }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ riskElement.description }}
                    </td>
                    <td class="d-none d-sm-table-cell">
                  <span
                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"
                    :class="{
                      'bg-success-light text-success': riskElement.active === true,
                      'bg-danger-light text-danger': riskElement.active === false
                    }">
                    {{ riskElement.active === true ? 'Activo' : 'Inhabilitado'}}
                    </span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                          data-bs-toggle="tooltip"
                          aria-label="Editar"
                          data-bs-original-title="Editar"
                          @click="router.visit(`/ebr_inherent_risk_catalog/${riskElement.id}`)"
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

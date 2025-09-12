<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
const page = usePage();

const props = defineProps({
  logs: Object,
  errors: Object,
})

const form = useForm({
  start_date: '',
  end_date: '',
});

function filter() {
  form.get('/logs/pld_notice', {})
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
</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Bitacoras"
    :subtitle="`Notificaciones`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Bitacoras
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">

    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit()"  enctype="multipart/form-data">
          <BaseBlock title="Notifiaciones" class="h-100 mb-0" content-class="fs-sm">
            <div class="row">

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Fecha Inicial <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control" :class="{ 'is-invalid': errors.start_date }"  id="start_date" name="start_date" placeholder="AAAA-MM-DD" v-model="form.start_date">
                  <div id="start_date-error" class="text-danger" >{{ errors.start_date }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Fecha Final <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control" :class="{ 'is-invalid': errors.end_date }"  id="end_date" name="end_date" placeholder="AAAA-MM-DD" v-model="form.end_date">
                  <div id="end_date-error" class="text-danger" >{{ errors.end_date }}</div>
                </div>
              </div>

            </div>

            <div class="row">
            <div class="col-2">
                <div class="mb-4">
                  <button type="button"  @click="filter" class="btn btn-info">Filtrar</button>
                </div>
              </div>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-hover table-sm table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th class="d-none d-sm-table-cell">Fecha</th>
                    <th class="d-none d-sm-table-cell">Usuario</th>
                    <th class="d-none d-sm-table-cell">Notificacion</th>
                    <th class="d-none d-sm-table-cell">Tipo</th>
                    <th class="d-none d-sm-table-cell">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(log, index) in logs.data" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ log.created_at }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(log.user_name) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(log.pld_notice) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(log.type) }}
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span
                        class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"
                        :class="{
                          'bg-success-light text-success': log.status === 'success',
                          'bg-danger-light text-danger': log.status === 'error',
                          'bg-warning-light text-warning': log.status === 'pending',
                        }">
                    {{ log.status }}
                    </span>
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


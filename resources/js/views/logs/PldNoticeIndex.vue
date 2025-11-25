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
  users: Object,
  pld_notices: Object,
})

const form = useForm({
  start_date: '',
  end_date: '',
  user_id: '',
  notice_id: ''
});

const showLog = ref({
  date: '',
  user: '',
  notice: '',
  type: '',
  status: '',
  content: '',
  pld_notice_spanish_name: ''
})

function loadLogInModal(log) {
  showLog.value.date = log.created_at
  showLog.value.user = log.user_name
  showLog.value.notice = log.pld_notice
  showLog.value.type = log.type
  showLog.value.status = log.status
  showLog.value.content = log.content
  showLog.value.pld_notice_spanish_name = log.pld_notice_spanish_name
}
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
                  <label class="form-label" for="month">Fecha Inicial </label>
                  <input type="text" class="form-control form-control" :class="{ 'is-invalid': errors.start_date }"  id="start_date" name="start_date" placeholder="AAAA-MM-DD" v-model="form.start_date">
                  <div id="start_date-error" class="text-danger" >{{ errors.start_date }}</div>
                </div>
              </div>

              <div class="col-2">
                <div class="mb-4">
                  <label class="form-label" for="month">Fecha Final </label>
                  <input type="text" class="form-control form-control" :class="{ 'is-invalid': errors.end_date }"  id="end_date" name="end_date" placeholder="AAAA-MM-DD" v-model="form.end_date">
                  <div id="end_date-error" class="text-danger" >{{ errors.end_date }}</div>
                </div>
              </div>

              <div class="col-3">
                <div class="mb-4">
                  <label class="form-label" for="month">Usuario </label>
                  <div class="mb-4">
                    <select
                      class="form-select"
                      id="user-select"
                      name="user-select"
                      v-model="form.user_id">
                      <option value="0">Selecciona una opción</option>
                      <option
                        v-for="(user, index) in users"
                        :key="index"
                        :value="user.id"
                      >
                        {{ user.name + ' ' + user.last_name }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-3">
                <div class="mb-4">
                  <label class="form-label" for="month">Aviso </label>
                  <div class="mb-4">
                    <select
                      class="form-select"
                      id="user-select"
                      name="user-select"
                      v-model="form.notice_id">
                      <option value="0">Selecciona una opción</option>
                      <option
                        v-for="(notice, index) in pld_notices"
                        :key="index"
                        :value="notice.id"
                      >
                        {{ notice.spanish_name }}
                      </option>
                    </select>
                  </div>
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
                    <th class="d-none d-sm-table-cell">Sujeto Obligado</th>
                    <th class="d-none d-sm-table-cell">Notificacion</th>
                    <th class="d-none d-sm-table-cell">Masivo</th>
                    <th class="d-none d-sm-table-cell">Tipo</th>
                    <th class="d-none d-sm-table-cell">Status</th>
                    <th class="d-none d-sm-table-cell">Detalle</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(log, index) in logs.data" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td>
                      {{ log.created_at }}
                    </td>
                    <td>
                      {{ toTitleCase(log.user_name) }}
                    </td>
                    <td>
                      {{ log.content.subject }}
                    </td>
                    <td>
                      {{ toTitleCase(log.pld_notice_spanish_name) }}
                    </td>
                    <td>
                      {{ log.content.massive ? 'Si' : 'No' }}
                    </td>
                    <td>
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
                    <td>
                      <button type="button" class="btn btn-sm btn-alt-secondary" @click="loadLogInModal(log)" data-bs-toggle="modal" data-bs-target="#modal-block-extra-large">
                        <i class="fa fa-magnifying-glass"></i>
                      </button>
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

  <!-- Extra Large Block Modal -->
  <div class="modal" id="modal-block-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-extra-large" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-transparent mb-0">
          <div class="block-header block-header-default">
            <h3 class="block-title">Bitacora</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <div class="row">
              <div class="col-6">
                <table class="table table-sm table-vcenter">
                  <tbody>
                  <tr>
                    <td class="fw-semibold fs-sm">
                      Fecha
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ showLog.date}}
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-semibold fs-sm">
                      Usuario
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ showLog.user}}
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-semibold fs-sm">
                      Notificacion
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ toTitleCase(showLog.pld_notice_spanish_name) }}
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-semibold fs-sm">
                      Tipo
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ showLog.type}}
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-semibold fs-sm">
                      Status
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ showLog.status}}
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>

              <div class="col-6">
                <p class="fw-semibold fs-sm">Detalle</p>
                <textarea class="form-control form-control-sm" id="log_details" name="log_details" rows="7">{{ showLog.content}}</textarea>
              </div>
            </div>
            <br>
          </div>
          <div class="block-content block-content-full text-end bg-body">
            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Extra Large Block Modal -->
</template>


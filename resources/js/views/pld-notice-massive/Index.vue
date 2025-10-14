<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed, onMounted } from "vue";
const page = usePage();

const props = defineProps({
  pldMassives: Object,
  allowedNotices: Array,
  errors: Object,
})

const noticeSelected = ref({
  template: '',
  route_param: '',
  id: 0,
  });

const hasFormErrors = ref(false);
const selectedId = ref(0);

const handleNoticeChange = () => {
  const found = props.allowedNotices.find(n => n.id == selectedId.value);

  if (found) {
    noticeSelected.value.template = found.template;
    noticeSelected.value.route_param = found.route_param;
  } else {
    noticeSelected.value.template = '';
    noticeSelected.value.route_param = '';
  }
};

const downloadTemplate = () => {
  const url = route('pld-notice.downloadTemplate', {noticeType: noticeSelected.value.route_param });

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', noticeSelected.value.template);
    document.body.appendChild(link);
    link.click();
    link.remove();
  })
}

const form = useForm({
  template: '',
  notice_id: '',
});

function submit() {
  hasFormErrors.value = false;
  form.notice_id = selectedId.value;
  form.post('/notification-pld-massive', {
    onSuccess: () => {
      form.reset();
    },
    onError: (e) => {
      hasFormErrors.value = true;
    }
  })
}

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Notificaciones - Masivas"
    :subtitle="`Notificaciones - Masivas`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Notificaciones Masivas
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">

    <div class="alert alert-danger alert-dismissible" role="alert" v-if="hasFormErrors">
      <p class="mb-0" v-for="error in errors">
        {{ error }}
      </p>
      <button type="button" class="btn-close" @click="hasFormErrors = false"></button>
    </div>

    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit()" enctype="multipart/form-data">
          <BaseBlock title="Notificaciones" class="h-100 mb-0" content-class="fs-sm">

            <div class="row">
              <div class="col-6">
                <div class="mb-4">
                  <label class="form-label" for="notice-select">Notificación<span class="text-danger">*</span></label>
                  <select
                    class="form-select"
                    id="notice-select"
                    name="notice-select"
                    @change="handleNoticeChange"
                    v-model="selectedId">
                    <option value="0">Selecciona una opción</option>
                    <option
                      v-for="(notice, index) in allowedNotices"
                      :key="index"
                      :value="notice.id"
                    >
                      {{ notice.spanish_name }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-6">
                <div class="mb-4">
                  <label class="form-label" for="file">Plantilla <span class="text-danger">*</span></label>
                  <input class="form-control" :class="{ 'is-invalid': errors.template }" type="file" id="template" name="template" @input="form.template = $event.target.files[0]">
                  <div id="template-error" class="text-danger">{{ errors.template}}</div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <button type="button" @click="downloadTemplate()" class="btn btn-info me-2">Plantilla</button>
              <button type="submit" class="btn btn-success me-2">Generar</button>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th class="d-none d-sm-table-cell">Archivo</th>
                    <th class="d-none d-sm-table-cell">Tiempo Restante</th>
                    <th class="d-none d-sm-table-cell">Status</th>
                    <th class="text-center" style="width: 100px;">Descargar</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(notice, index) in pldMassives" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ notice.original_name }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      25:20
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span
                        class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"
                        :class="{
                          'bg-success-light text-success': notice.status === 'done',
                          'bg-danger-light text-danger': notice.status === 'error',
                          'bg-warning-light text-warning': notice.status === 'processing'
                        }">
                        {{ notice.status }}
                        </span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group" v-if="notice.status == 'done'">
                        <a
                          :href="`/storage/ebr_reports/reporte_ebr_${notice.id}.xlsx`"
                          download
                          class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled"
                          data-bs-toggle="tooltip"
                          aria-label="Descargar reporte"
                          title="Descargar reporte"
                        >
                          <i class="fa fa-fw fa-file-arrow-down"></i>
                        </a>
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

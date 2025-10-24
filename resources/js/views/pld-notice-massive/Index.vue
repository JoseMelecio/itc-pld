<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, onMounted, onBeforeUnmount, computed } from "vue";
import { router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const page = usePage();
const countdowns = ref<{ [key: number]: string }>({});
let interval: number;

onMounted(() => {
  // Intervalo para actualizar los contadores cada segundo
  const updateCountdowns = () => {
    const now = dayjs();
    filteredPldMassives.value.forEach(notice => {
      const created = dayjs(notice.created_at);
      const diff = 30 * 60 - now.diff(created, 'second'); // 30 minutos - segundos transcurridos
      if (diff > 0) {
        const minutes = Math.floor(diff / 60).toString().padStart(2, '0');
        const seconds = (diff % 60).toString().padStart(2, '0');
        countdowns.value[notice.id] = `${minutes}:${seconds}`;
      } else {
        countdowns.value[notice.id] = "00:00";
      }
    });
  };

  updateCountdowns(); // inicializa el contador
  const countdownInterval = setInterval(updateCountdowns, 1000);

  // Intervalo para recargar los datos cada 30 segundos con Inertia
  const reloadInterval = setInterval(() => {
    router.reload({ only: ['pldMassives'] });
  }, 30000);

  // Limpiar ambos intervalos cuando el componente se destruye
  onBeforeUnmount(() => {
    clearInterval(countdownInterval);
    clearInterval(reloadInterval);
  });
});

const filteredPldMassives = computed(() => {
  const now = dayjs();
  return props.pldMassives.filter(notice => {
    const created = dayjs(notice.created_at);
    const diffMinutes = now.diff(created, 'minute');
    return diffMinutes < 30; // solo registros creados hace menos de 30 minutos
  });
});

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
  const url = route('notification-pld-massive.download-template', {noticeType: noticeSelected.value.route_param });

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const massiveName = noticeSelected.value.template.split('.').slice(0, -1).join('.') + 'Masivo.xlsx';

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', massiveName);
    document.body.appendChild(link);
    link.click();
    link.remove();
  })
}

const form = useForm({
  template: '',
  notice_id: '',
  month: '',
  collegiate_entity_tax_id: '',
  notice_reference: '',
  exempt: 'no',
  occupation_type: '',
  occupation_description: '',
  file: '',
  validate_xsd_xml: true,
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

const showLog = ref({
  error: '',
})

function loadLogInModal(log) {
  showLog.value.error = log.errors
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
              <div class="col-3">
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

              <div class="col-3">
                <div class="mb-4">
                  <label class="form-label" for="month">Mes reportado <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.month }"  id="month" name="month" placeholder="AAAAMM" v-model="form.month">
                  <div id="month-error" class="text-danger" >{{ errors.month }}</div>
                </div>
              </div>

              <div class="col-3">
                <div class="mb-4">
                  <label class="form-label" for="collegiate_entity_tax_id">RFC Entidad colegiada</label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.collegiate_entity_tax_id }"  id="collegiate_entity_tax_id" name="collegiate_entity_tax_id" placeholder="XAXX010101000" v-model="form.collegiate_entity_tax_id">
                  <div id="collegiate_entity_tax_id-error" class="invalid-feedback animated fadeIn">{{ errors.collegiate_entity_tax_id}}</div>
                </div>
              </div>

              <div class="col-3">
                <div class="mb-4">
                  <label class="form-label" for="notice_reference">Referencia del aviso<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.notice_reference }"  id="notice_reference" name="notice_reference" placeholder="Referencia" v-model="form.notice_reference">
                  <div id="notice_reference-error" class="invalid-feedback animated fadeIn">{{ errors.notice_reference}}</div>
                </div>
              </div>
            </div>

              <div class="row">
                <div class="col-3">
                  <div class="mb-4">
                    <label class="form-label" for="exempt">Exento <span class="text-danger">*</span></label>
                    <select class="form-select" :class="{ 'is-invalid': errors.exempt }" id="exempt" name="exempt" v-model="form.exempt">
                      <option value="no">No</option>
                      <option value="yes">Si</option>
                    </select>
                    <div id="exempt-error" class="text-danger">{{ errors.exempt}}</div>
                  </div>
                </div>

                <div class="col-3">
                  <div class="mb-4">
                    <label class="form-label" for="occupation_type">Ocupación <span class="text-danger">*</span></label>
                    <select class="form-select" :class="{ 'is-invalid': errors.occupation_type }" id="occupation_type" name="occupation_type" v-model="form.occupation_type">
                      <option value="1">Abogado</option>
                      <option value="2">Contador</option>
                      <option value="3">Administrador</option>
                      <option value="4">Outsourcing / Servicios Especializados</option>
                      <option value="5">Consultoría</option>
                      <option value="99">Otro</option>
                    </select>
                    <div id="occupation_type-error" class="text-danger">{{ errors.occupation_type}}</div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="mb-4">
                    <label class="form-label" for="occupation_description">Descripción  de la ocupación</label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.occupation_description }"  id="occupation_description" name="occupation_description" placeholder="Descripcion de la ocupacion" v-model="form.occupation_description">
                    <div id="occupation_description-error" class="invalid-feedback animated fadeIn">{{ errors.occupation_description}}</div>
                  </div>
                </div>
              </div>


            <div class="row">
              <div class="col-12">
                <div class="mb-4">
                  <label class="form-label" for="file">Plantilla <span class="text-danger">*</span></label>
                  <input class="form-control" :class="{ 'is-invalid': errors.template }" type="file" id="template" name="template" @input="form.template = $event.target.files[0]">
                  <div id="template-error" class="text-danger">{{ errors.template}}</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-3">
                <div class="mb-4">
                  <div class="space-y-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="validate_xsd_xml" name="validate_xsd_xml" v-model="form.validate_xsd_xml">
                      <label class="form-check-label" for="validate_xsd_xml">Activar Validación XSD</label>
                    </div>
                  </div>
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
                  <tr v-for="(notice, index) in filteredPldMassives" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ notice.original_name }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ countdowns[notice.id] || '30:00' }}
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
                          :href="`/storage/${notice.xml_generated}`"
                          :download="`${notice.xml_generated}`"
                          class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled"
                          data-bs-toggle="tooltip"
                          aria-label="Descargar reporte"
                          title="Descargar reporte"
                        >
                          <i class="fa fa-fw fa-file-arrow-down"></i>
                        </a>
                      </div>
                      <div class="btn-group" v-if="notice.status == 'error'">
                        <button type="button" @click="loadLogInModal(notice)"
                          class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled"
                          data-bs-toggle="modal"
                          data-bs-target="#modal-block-extra-large"
                        >
                          <i class="fa fa-fw fa-magnifying-glass"></i>
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

  <!-- Extra Large Block Modal -->
  <div class="modal" id="modal-block-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-extra-large" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-transparent mb-0">
          <div class="block-header block-header-default">
            <h3 class="block-title">Errores</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <div class="row">
              <div class="col-12">
                <p class="fw-semibold fs-sm">Detalle</p>
                <textarea class="form-control form-control-sm" id="log_details" name="log_details" rows="7">{{ showLog.error }}</textarea>
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

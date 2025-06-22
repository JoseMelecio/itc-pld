<script setup lang="ts">

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {ref, computed} from "vue";
const page = usePage();

const props = defineProps({
  ebrs: Object,
  ebrTypeUser: Object,
  errors: Object,
})

const form = useForm({
  file_clients: '',
  file_operations: '',
  ebr_type_id: '',
});

function submit() {
  form.post('/ebr')
}

const hasFormErrors = ref(false);
const formErrors = ref([]);


const downloadClientTemplate = () => {
  const url = route('ebr.downloadClientTemplate');

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', 'EBR Plantilla Clientes.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
  })
}

const downloadOperationTemplate = () => {
  const url = route('ebr.downloadOperationTemplate');

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', 'EBR Plantilla Operaciones.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
  })
}

const downloadDemoEBR = () => {
  const url = route('ebr.downloadDemo');

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', 'EBRAgentesRelacionadosDemo.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
  })
}

const tiempoRestanteMap = ref<Record<string, string>>({})

const tiempoRestante = (fechaStr: string) => {
  if (!tiempoRestanteMap.value[fechaStr]) {
    const calcular = () => {
      const inicio = new Date(fechaStr)
      const fin = new Date(inicio.getTime() + 30 * 60 * 1000)
      const ahora = new Date()
      const diferencia = fin.getTime() - ahora.getTime()

      if (diferencia <= 0) {
        tiempoRestanteMap.value[fechaStr] = "00:00"
        clearInterval(intervals[fechaStr])
        return
      }

      const minutos = String(Math.floor(diferencia / 1000 / 60)).padStart(2, '0')
      const segundos = String(Math.floor((diferencia / 1000) % 60)).padStart(2, '0')

      tiempoRestanteMap.value[fechaStr] = `${minutos}:${segundos}`
    }

    calcular()
    const interval = setInterval(calcular, 1000)
    intervals[fechaStr] = interval
  }

  return tiempoRestanteMap.value[fechaStr] || "00:00"
}

const intervals: Record<string, number> = {}


const esValido = (fechaStr: string) => {
  const inicio = new Date(fechaStr)
  const fin = new Date(inicio.getTime() + 30 * 60 * 1000)
  const ahora = new Date()
  return ahora.getTime() < fin.getTime()
}

const ebrsValidos = computed(() => {
  return props.ebrs.filter((ebr: any) => esValido(ebr.created_at))
})

const statusTranslate = (status: string) => {
  switch (status) {
    case 'processing':
      return 'Procesando'
    case 'ready':
      return 'Listo'
    case 'failed':
      return 'Fallido'
  }
}

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="EBR"
    :subtitle="`Generador de EBR`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Generar EBR
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
          <BaseBlock title="EBR" class="h-100 mb-0" content-class="fs-sm">

            <div class="row">
              <div class="col-12">
                <div class="mb-4">
                  <label class="form-label" for="file">Archivo de clientes <span class="text-danger">*</span></label>
                  <input class="form-control" :class="{ 'is-invalid': errors.file_clients }" type="file" id="file_clients" name="file_clients" @input="form.file_clients = $event.target.files[0]">
                  <div id="file_clients-error" class="text-danger">{{ errors.file_clients}}</div>
                </div>

                <div class="mb-4">
                  <label class="form-label" for="file">Archivo de operaciones <span class="text-danger">*</span></label>
                  <input class="form-control" :class="{ 'is-invalid': errors.file_operations }" type="file" id="file_operations" name="file_operations" @input="form.file_operations = $event.target.files[0]">
                  <div id="file_operations-error" class="text-danger">{{ errors.file_operations}}</div>
                </div>

                <div class="mb-4">
                  <label class="form-label" for="example-select">Tipo EBR</label>
                  <select class="form-select" name="ebr_type" id="ebr_type" v-model="form.ebr_type_id">
                    <option v-for="ebrType in ebrTypeUser" :key="ebrType.id" :value="ebrType.id">{{ ebrType.type.toUpperCase() }}</option>
                  </select>
                  <div id="evt_type-error" class="text-danger">{{ errors.ebr_type }}</div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <button type="button" @click="downloadClientTemplate()" class="btn btn-info me-2">Plantilla Clientes</button>
              <button type="button" @click="downloadOperationTemplate()" class="btn btn-info me-2">Plantilla Operaciones</button>
              <button type="submit" class="btn btn-success me-2">Generar</button>
            </div>

            <hr>

            <div class="block-content block-content-full">

              <div class="row">
                <table class="table table-vcenter">
                  <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>ID</th>
                    <th class="d-none d-sm-table-cell">Archivo</th>
                    <th class="d-none d-sm-table-cell">Tiempo Restante</th>
                    <th class="d-none d-sm-table-cell">Status</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(ebr, index) in ebrsValidos" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      {{ ebr.id }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ ebr.file_name }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ tiempoRestante(ebr.created_at) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ statusTranslate(ebr.status) }}
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit Client" data-bs-original-title="Edit Client">
                          <i class="fa fa-fw fa-file-arrow-down"></i>
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

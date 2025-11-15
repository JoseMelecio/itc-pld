<script setup lang="ts">

import { router, useForm } from "@inertiajs/vue3";
import {route} from "ziggy-js";
import axios from "axios";
import { computed, onMounted, ref } from "vue";
import formatters from "chart.js/dist/core/core.ticks";

const props = defineProps({
  errors: Object,
  massiveFinds: Object,
})

const form = useForm({
  file: '',
});

function submit() {
  form.post('/person-blocked-form-finder-massive', {
    onSuccess: () => {
      form.reset();
    }
  })
}


const downloadTemplate = () => {
  const url = route('person-list-blocked-download-template');

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', 'plantillaBuscarPersonasBloqueadas.xlsx');
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

const massiveFindsValidos = computed(() => {
  return props.massiveFinds.filter((ebr: any) => esValido(ebr.created_at))
})

const statusTranslate = (status: string) => {
  switch (status) {
    case 'pending':
      return 'Procesando'
    case 'done':
      return 'Listo'
    case 'failed':
      return 'Fallido'
  }
}

onMounted(() => {
  setInterval(() => {
    router.reload({ only: ['massiveFinds'] })
  }, 60000)
})

const resumen = ref({})
const setResumen = (id: number) => {
  resumen.value = massiveFindsValidos.value.find(e => e.id == id)
}

function formatFecha(fechaISO) {
  const date = new Date(fechaISO)

  const meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
  ]

  const dia = date.getUTCDate()
  const mes = meses[date.getUTCMonth()]
  const año = date.getUTCFullYear()

  let horas = date.getUTCHours()
  const minutos = date.getUTCMinutes().toString().padStart(2, "0")

  const ampm = horas >= 12 ? "pm" : "am"
  horas = horas % 12 || 12 // Convierte 0 a 12, 13 → 1, etc.

  return `${dia} de ${mes} del ${año} ${horas}:${minutos} ${ampm}`
}

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Personas Bloqueadas"
    :subtitle="`Buscador de personas bloqueadas`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Generar notificaciones
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit">
          <BaseBlock title="Buscar" class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">

              <div class="row">
                <div class="mb-4">
                  <label class="form-label" for="file">Archivo de Excel</label>
                  <input class="form-control" :class="{ 'is-invalid': errors.file }" type="file" id="file" name="file" @input="form.file = $event.target.files[0]">
                  <div id="file-error" class="text-danger">{{ errors.file}}</div>
                </div>
              </div>

              <div class="mb-4">
                <button type="submit" class="btn btn-success me-2">Buscar</button>
                <button type="button" @click="downloadTemplate()" class="btn btn-info me-2">Plantilla</button>
                <button type="button" class="btn btn-alt-secondary" data-bs-toggle="modal" data-bs-target="#modal-block-extra-large">Ayuda</button>
              </div>

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
                  <tr v-for="(find, index) in massiveFindsValidos" :key="index">
                    <th class="text-center" scope="row">{{ index + 1 }}</th>
                    <td class="fw-semibold fs-sm">
                      <div>{{ find.file_uploaded }}</div>
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ tiempoRestante(find.created_at) }}
                    </td>
                    <td class="fw-semibold fs-sm">
                      {{ statusTranslate(find.status) }}
                    </td>
                    <td class="text-center">
                      <div class="btn-group" v-if="find.status == 'done'">
                        <a
                          :href="`/storage/${find.download_file_name}`"
                          download
                          class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled"
                          data-bs-toggle="tooltip"
                          aria-label="Descargar reporte"
                          title="Descargar reporte"
                        >
                          <i class="fa fa-fw fa-file-arrow-down"></i>
                        </a>
                      </div>
                      <div class="btn-group" v-if="find.status == 'done'">
                        <button type="button" @click="setResumen(find.id)"
                                class="btn btn-sm btn-alt-primary js-bs-tooltip-enabled"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-resume">
                          <i class="fa fa-fw fa-magnifying-glass"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>


            </div>

          </BaseBlock>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Resume -->
  <div class="modal" id="modal-resume" tabindex="-1" role="dialog" aria-labelledby="modal-resume" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-transparent mb-0">
          <div class="block-header block-header-default">
            <h3 class="block-title">Resumen de la busqueda</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <div class="row">
              <div class="col-6">
                <div class="mb-4">
                  <h6>Barrido de Listas de Personas Bloqueadas UIF</h6>
                </div>

                <div class="mb-4">
                  <p><strong>Personas y Entidades</strong></p>
                  <p>Creación del reporte: {{ formatFecha(resumen.created_at) }}</p>
                  <p>Total de personas o empresas en el archivo de Excel: {{ resumen.total_rows }}</p>
                  <p>Personas o empresas sin coincidencias: {{ resumen.total_rows - resumen.matches }}</p>
                  <p>Personas o empresas con coincidencias: {{ resumen.matches }}</p>
                </div>

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

  <!-- Extra Large Block Modal -->
  <div class="modal" id="modal-block-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-extra-large" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-transparent mb-0">
          <div class="block-header block-header-default">
            <h3 class="block-title">Ayuda</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <div class="row">
              <div class="col-6">
                <div class="mb-4">
                  <p>Descargue la plantilla, capture los datos y posteriormente importe el archivo Excel en el sistema.
                  Recuerde que las fechas deben utilizar el formato: <strong>AAAAMMDD</strong>.</p>
                </div>

                <div class="mb-4">
                  <h6>Definición:</h6>
                  <strong>Estricta:</strong> coincide nombre, alias y fecha de nacimiento/constitución.<br>                  <strong>Exacta:</strong> coincide nombre y fecha de nacimiento o alias y fecha de nacimiento.<br>
                  <strong>Aproximada:</strong> coincide alias o nombre.<br>
                </div>

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

<style scoped>

</style>

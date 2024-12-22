<script setup>

import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import axios from "axios";

const props = defineProps({
  errors: Object,
})

const form = useForm({
  name: '',
  alias: '',
  date: '',
  file: '',
});

const submit = async () => {
  try {
    const response = await axios.post(route('person-list-blocked-find'), form, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      responseType: 'blob', // Necesario para manejar archivos binarios

    });

    // Crear un enlace para descargar el archivo
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;

    // Extraer el nombre del archivo del encabezado Content-Disposition
    const contentDisposition = response.headers['content-disposition'];
    const fileName = contentDisposition
      ? contentDisposition.split('filename=')[1].replace(/['"]/g, '')
      : 'archivo.pdf';

    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();

    // Limpiar el enlace después de usarlo
    link.parentNode.removeChild(link);
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Error al descargar el archivo:', error);
  }
};

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

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Notificaciones"
    :subtitle="`Generador de notificaciones`"
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
                  <label class="form-label" for="month">Nombre</label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }"  id="name" name="name" placeholder="Nombre" v-model="form.name">
                  <div id="name-error" class="text-danger" >{{ errors.name }}</div>
                </div>
              </div>

              <div class="row">
                <div class="mb-4">
                  <label class="form-label" for="month">Alias</label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.alias }"  id="alias" name="alias" placeholder="Alias" v-model="form.alias">
                  <div id="alias-error" class="text-danger" >{{ errors.alias }}</div>
                </div>
              </div>

              <div class="row">
                <div class="mb-4">
                  <label class="form-label" for="month">Fecha nacimiento/constitución</label>
                  <input type="text" class="form-control" :class="{ 'is-invalid': errors.date }"  id="date" name="date" placeholder="AAAAMMDD" v-model="form.date">
                  <div id="date-error" class="text-danger" >{{ errors.date }}</div>
                </div>
              </div>

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
                <!--              <button type="button" class="btn btn-light me-2">Ayuda</button>-->
              </div>

              <div class="row">
                <div class="mb-4">
                  <h6>Ayuda:</h6>
                  Capture el formulario (al menos el nombre) o suba la plantilla.<br><br>
                  <h6>Definición:</h6>
                  <strong>Estricta:</strong> coincide nombre, alias y fecha de nacimiento/constitución.<br>
                  <strong>Exacta:</strong> coincide nombre y fecha de nacimiento o alias y fecha de nacimiento.<br>
                  <strong>Aproximada:</strong> coincide alias o nombre.<br>
                </div>
              </div>


            </div>

          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

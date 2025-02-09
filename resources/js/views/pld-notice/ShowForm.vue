<script setup>

import {route} from "ziggy-js";
import { useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import { ref} from "vue";
const page = usePage();

const props = defineProps({
  errors: Object,
  pldNotice: null,
})

const form = useForm({
  pld_notice_id: props.pldNotice.id,
  month: '',
  collegiate_entity_tax_id: '',
  notice_reference: '',
  exempt: 'no',
  file: '',
});

const hasFormErrors = ref(false);
const formErrors = ref([]);

const submit = async () => {
  hasFormErrors.value = false;
  try {
    const response = await axios.post(route('pld-notice.makeNotice'), form, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      responseType: 'blob',

    });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;

    const contentDisposition = response.headers['content-disposition'];
    const fileName = contentDisposition
      ? contentDisposition.split('filename=')[1].replace(/['"]/g, '')
      : 'archivo.xml';

    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();

    link.parentNode.removeChild(link);
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Error al descargar el archivo:', error);
    if (error.response) {
      const reader = new FileReader();
      reader.onload = function () {
        try {
          const errorData = JSON.parse(reader.result);
          hasFormErrors.value = true;
          formErrors.value =  Object.values(errorData.errors).flat();
        } catch (e) {
          console.log('No se pudo leer el error:', reader.result);
        }
      };
      reader.readAsText(error.response.data);
    }
  }
};

const showGenerarButton = page.props.auth?.user?.status === "active";

const downloadTemplate = () => {
  const url = route('pld-notice.downloadTemplate', {noticeType: props.pldNotice.route_param });

  axios({
    url: url,
    method: 'GET',
    responseType: 'blob',
  }).then((response) => {
    const blob = new Blob([response.data]);
    const downloadUrl = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', props.pldNotice.template);
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

    <div class="alert alert-danger alert-dismissible" role="alert" v-if="hasFormErrors">
      <p class="mb-0" v-for="error in formErrors">
        {{ error }}
      </p>
      <button type="button" class="btn-close" @click="hasFormErrors = false"></button>
    </div>


    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit">
          <BaseBlock :title="pldNotice.spanish_name" class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">
              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="month">Mes reportado <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.month }"  id="month" name="month" placeholder="AAAAMM" v-model="form.month">
                    <div id="month-error" class="text-danger" >{{ errors.month }}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="collegiate_entity_tax_id">RFC Entidad colegiada</label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.collegiate_entity_tax_id }"  id="collegiate_entity_tax_id" name="collegiate_entity_tax_id" placeholder="XAXX010101000" v-model="form.collegiate_entity_tax_id">
                    <div id="collegiate_entity_tax_id-error" class="invalid-feedback animated fadeIn">{{ errors.collegiate_entity_tax_id}}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="notice_reference">Referencia del aviso</label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.notice_reference }"  id="notice_reference" name="notice_reference" placeholder="Referencia.." v-model="form.notice_reference">
                    <div id="notice_reference-error" class="invalid-feedback animated fadeIn">{{ errors.notice_reference}}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="exempt">Exento <span class="text-danger">*</span></label>
                    <select class="form-select" :class="{ 'is-invalid': errors.exempt }" id="exempt" name="exempt" v-model="form.exempt">
                      <option value="no">No</option>
                      <option value="yes">Si</option>
                    </select>
                    <div id="exempt-error" class="text-danger">{{ errors.exempt}}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="file">Archivo de Excel <span class="text-danger">*</span></label>
                    <input class="form-control" :class="{ 'is-invalid': errors.file }" type="file" id="file" name="file" @input="form.file = $event.target.files[0]">
                    <div id="file-error" class="text-danger">{{ errors.file}}</div>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <button type="submit" class="btn btn-success me-2" v-if="showGenerarButton">Generar</button>
                <button type="button" @click="downloadTemplate()" class="btn btn-info me-2">Plantilla</button>
                <!--              <button type="button" class="btn btn-light me-2">Ayuda</button>-->
              </div>



            </div>

          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
  errors: Object,
  pldNotice: '',
})

const form = useForm({
  month: '',
  collegiate_entity_tax_id: null,
  notice_reference: null,
  exempt: 'no',
  file: null,
});

const submit = () => {
  console.log("submite");
  router.post(route('pld-notice.makeNotice'), form)
};
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
          <BaseBlock :title="pldNotice.spanish_name" class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">
              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="month">Mes reportado <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.month }"  id="month" name="month" placeholder="MMAAAA" v-model="form.month">
                    <div id="month-error" class="invalid-feedback animated fadeIn">asdsad</div>
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
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.notice_reference }"  id="notice_reference" name="notice_reference" placeholder="Referencia.." v-model="form.collegiate_entity_tax_id">
                    <div id="notice_reference-error" class="invalid-feedback animated fadeIn">{{ errors.notice_reference}}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="example-select">Exento <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" v-model="form.exempt">
                      <option value="no">No</option>
                      <option value="yes">Si</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="file">Archivo de Excel</label>
                    <input class="form-control" type="file" id="file" name="file" @input="form.file = $event.target.files[0]">
                    <div id="file-error" class="invalid-feedback animated fadeIn">{{ errors.file}}</div>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <button type="submit" class="btn btn-success me-2">Generar</button>
                <!--              <button type="button" class="btn btn-info me-2">Plantilla</button>-->
                <!--              <button type="button" class="btn btn-light me-2">Ayuda</button>-->
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

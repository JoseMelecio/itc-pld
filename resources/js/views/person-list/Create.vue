<script setup>

import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
  errors: Object,
})

const form = useForm({
  first_name: '',
  second_name: '',
  third_name: '',
  origin: '',
  record_type: '',
  un_list_type: '',
  file: '',
  alias: '',
  birth_date: '',
  birth_place: '',
})
const submit = () => {
  router.post(route('person-blocked-list.store'), form, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
    onFinish: () => clearForm(),
  })
};

const clearForm = () => {
  form.reset();
};

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Personas bloqueadas"
    :subtitle="`Agregar Persona Persona Bloqueada`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/users">Usuarios</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Nueva Persona Bloqueada
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <form @submit.prevent="submit">
      <div class="row items-push">
        <div class="col-sm-12 col-xl-12">
          <BaseBlock :title="'Nuevo Usuario'"  class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">
              <div class="row">
                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="origin">Origen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="origin" name="origin" placeholder="PROPORCIONADO POR LA UIF" v-model="form.origin">
                    <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.origin}}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="un_list_type">Nombre de la lista <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="un_list_type" name="un_list_type" placeholder="LISTADO RELATIVO A HAITI" v-model="form.un_list_type">
                    <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.un_list_type}}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="record_type">Tipo de registo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="record_type" name="record_type" placeholder="PERSONAS" v-model="form.record_type">
                    <div id="record_type-error" class="invalid-feedback animated fadeIn">{{ errors.record_type }}</div>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="first_name">Primer Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.first_name }" id="first_name" name="first_name" placeholder="PRIMER NOMBRE" v-model="form.first_name">
                    <div id="first_name-error" class="invalid-feedback animated fadeIn">{{ errors.first_name}}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="second_name">Segundo Nombre </label>
                    <input type="text" class="form-control" id="second_name" name="second_name" placeholder="SEGUNDO NOMBRE" v-model="form.second_name">
                    <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.second_name}}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="third_name">Tercer Nombre </label>
                    <input type="text" class="form-control" id="third_name" name="third_name" placeholder="TERCER NOMBRE" v-model="form.third_name">
                    <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.third_name}}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="third_name">Alias </label>
                    <textarea class="form-control" id="alias" name="alias" rows="4" placeholder="ALIAS" v-model="form.alias"></textarea>
                    <div id="third_name-error" class="invalid-feedback animated fadeIn">{{ errors.alias }}</div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="birth_date">Fechas de nacimiento</label>
                    <textarea class="form-control" id="birth_date" name="birth_date" rows="4" placeholder="AAAAMMDD" v-model="form.birth_date"></textarea>
                    <div id="third_name-error" class="invalid-feedback animated fadeIn">{{ errors.birth_date }}</div>
                  </div>
                </div>


                <div class="col-4">
                  <div class="mb-4">
                    <label class="form-label" for="birth_place">Lugares de nacimiento </label>
                    <textarea class="form-control" id="birth_place" name="birth_place" rows="4" placeholder="LUGARES DE NACIMIENTO" v-model="form.birth_place"></textarea>
                    <div id="third_name-error" class="invalid-feedback animated fadeIn">{{ errors.birth_place }}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <label class="form-label" for="file">PDF <span class="text-danger">*</span></label>
                    <input class="form-control" :class="{ 'is-invalid': errors.file }" type="file" id="file" name="file" @input="form.file = $event.target.files[0]">
                    <div id="file-error" class="text-danger">{{ errors.file}}</div>
                  </div>
                </div>
              </div>



              <div class="row">
                <div class="col-3">
                  <div class="mb-4">
                    <button type="button" @click="submit()" class="btn btn-success">Guardar</button>
                  </div>
                </div>
              </div>
            </div>
          </BaseBlock>
        </div>

      </div>
    </form>
  </div>
</template>


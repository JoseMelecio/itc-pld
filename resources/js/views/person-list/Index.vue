<script setup>

import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';

DataTable.use(DataTablesCore);

const columns = [
  { data: 'name' },
  { data: 'origin' },
  { data: 'record_type' },
  { data: 'un_list_type' },
  { data: 'alias' },
  { data: 'birth_date' },
  { data: 'birth_place' },
  { data: 'document' },
];

const props = defineProps({
  person_list: Object
})

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Personas Bloqueadas"
    :subtitle="`Lista de personas bloqueadas`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Lista de personas bloqueadas
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <form @submit.prevent="submit">
          <BaseBlock title="Personas Bloqueadas" class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">

              <DataTable :data="person_list"  :columns="columns" class="display table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Origen</th>
                  <th>Tipo de Registro</th>
                  <th>Tipo de Lista</th>
                  <th>Alias</th>
                  <th>Fecha Nacimiento</th>
                  <th>Lugar Nacimiento</th>
                  <th>Documento</th>
                </tr>
                </thead>
              </DataTable>

              <div class="mb-4">
<!--                <button type="submit" class="btn btn-success me-2">Generar</button>-->
<!--                <button type="button" class="btn btn-info me-2">Plantilla</button>-->
                <!--              <button type="button" class="btn btn-light me-2">Ayuda</button>-->
              </div>



            </div>

          </BaseBlock>
        </form>
      </div>
    </div>
  </div>
</template>

<style>

</style>


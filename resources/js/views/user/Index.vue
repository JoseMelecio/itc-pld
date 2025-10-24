<script setup>
import {Link, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const props = defineProps({
  users: {
    data: {}
  },
})

const formatPhoneNumber = (number) => {
  const cleaned = number.replace(/\D/g, '');
  return cleaned.replace(
    /(\d{2})(\d{2})(\d{4})(\d{4})/,
    '+$1-$2-$3-$4'
  );
};

const createUser = () => {
  const url = route('users.create')
  router.visit(url);
}

const editUser = (id) => {
  router.visit(
    route('users.show', { user: id }),
    { method: 'get' });
}

const eraseCache = () => {
  router.patch(route('users.erase-cache'))
}

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Usuarios"
    :subtitle="`Administración de usuarios`"
  >
    <template #extra>
      <button type="button" class="btn btn-alt-primary" @click="createUser">
        <i class="fa fa-plus opacity-50 me-1"></i>
        Nuevo Usuario
      </button>

    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row items-push">
      <div class="col-sm-12 col-xl-12">
        <div class="col-2">
          <button type="button" class="btn btn-alt-warning" @click="eraseCache">
            <i class="fa fa-trash opacity-50 me-1"></i>
            Borrar Cache
          </button>
        </div>
      </div>
        <div class="row">
        <BaseBlock title="Usuarios" class="h-100 mb-0" content-class="fs-sm">
            <table class="table table-hover table-vcenter">
              <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Celular</th>
                <th>RFC</th>
                <th>Tipo Usuario</th>
                <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
                <th class="text-center" style="width: 100px;">Acciones</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(user, index) in users.data" :key="user.id">
                <th class="text-center" scope="row">{{ index + 1 }}</th>
                <td class="fw-semibold fs-sm text-primary">
                  {{ user.last_name + ' ' + user.name }}
                </td>
                <td class="fw-semibold fs-sm">
                  {{ user.email}}
                </td>
                <td class="fw-semibold fs-sm">
                  {{ formatPhoneNumber(user.phone) }}
                </td>
                <td class="fw-semibold fs-sm">
                  {{ user.tax_id }}
                </td>
                <td class="fw-semibold fs-sm">
                  {{ user.user_type }}
                </td>
                <td class="d-none d-sm-table-cell">
                  <span
                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"
                    :class="{
                      'bg-success-light text-success': user.status === 'active',
                      'bg-danger-light text-danger': user.status === 'disabled',
                      'bg-warning-light text-warning': user.status === 'suspended'
                    }">
                    {{ user.status }}
                    </span>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <button type="button" @click="editUser(user.id)" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edit Client" data-bs-original-title="Edit Client">
                      <i class="fa fa-fw fa-pencil-alt"></i>
                    </button>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>

        </BaseBlock>
      </div>
    </div>
  </div>
</template>

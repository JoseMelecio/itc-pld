<script setup>

import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
  errors: Object,
  permissions: Object,
  user: {
    type: Object,
    default: () => ({
      id: null,
      user_name: null,
      name: null,
      last_name: null,
      tax_id: null,
      email: null,
      phone: null,
      password: null,
      password_confirmation: null,
      status: null,
    }),
  },
})

const form = useForm({
  ...props.user,
  permissions: props.permissions,
})
const submit = () => {
  if (props.user.id) {
    router.patch(route('users.update', { user: props.user.id}), form)
  } else {
    router.post(route('users.store'), form)
  }
};

const clearForm = () => {
  form.reset();
};

</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading
    title="Usuarios"
    :subtitle="user.id ? `Editar usuario` : `Nuevo usuario`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/users">Usuarios</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            {{ user.id ? 'Editar usuario' : 'Nuevo usuario' }}
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <form @submit.prevent="submit">
      <div class="row items-push">
        <div class="col-sm-12 col-xl-12">
          <BaseBlock :title="user.id ? 'Usuario: ' + user.name + ' ' + user.last_name : 'Nuevo Usuario'"  class="h-100 mb-0" content-class="fs-sm">

            <div class="block-content block-content-full">
                  <div class="row">
                    <div class="col-2">
                      <div class="mb-4">
                        <label class="form-label" for="user_name">Usuario <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.user_name }"  id="user_name" name="user_name" placeholder="Usuario.." v-model="form.user_name">
                        <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.user_name}}</div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="mb-4">
                        <label class="form-label" for="name">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }"  id="name" name="name" placeholder="Nombre.." v-model="form.name">
                        <div id="name-error" class="invalid-feedback animated fadeIn">{{ errors.name}}</div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="mb-4">
                        <label class="form-label" for="last_name">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.last_name }" id="last_name" name="last_name" placeholder="Apellidos.." v-model="form.last_name">
                        <div id="last_name-error" class="invalid-feedback animated fadeIn">{{ errors.last_name }}</div>
                      </div>
                    </div>

                    <div class="col-2">
                      <div class="mb-4">
                        <label class="form-label" for="tax-id">RFC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.tax_id }" id="tax-id" name="tax-id" placeholder="RFC.." v-model="form.tax_id">
                        <div id="tax-id-error" class="invalid-feedback animated fadeIn">{{ errors.tax_id}}</div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="mb-4">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.email }" id="email" name="email" placeholder="Email.." v-model="form.email">
                        <div id="email-error" class="invalid-feedback animated fadeIn">{{ errors.email }}</div>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="mb-4">
                        <label class="form-label" for="phone">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.phone }" id="phone" name="phone" placeholder="Teléfono.." v-model="form.phone">
                        <div id="phone-error" class="invalid-feedback animated fadeIn">{{ errors.phone }}</div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="mb-4">
                        <label class="form-label" for="password">Contraseña <span class="text-danger" v-if="!form.id">*</span></label>
                        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password" name="password" placeholder="Contraseña.." v-model="form.password">
                        <div id="password-error" class="invalid-feedback animated fadeIn">{{ errors.password }}</div>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="mb-4">
                        <label class="form-label" for="confirm_password">Confirmar contraseña <span class="text-danger" v-if="!form.id">*</span></label>
                        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password_confirmation }" id="confirm_password" name="confirm_password" placeholder="Confirmar contraseña.." v-model="form.password_confirmation">
                        <div id="confirm_password-error" class="invalid-feedback animated fadeIn">{{ errors.password_confirmation }}</div>
                      </div>
                    </div>
                  </div>

                  <div class="row" v-if="props.user.id">
                    <div class="col-3">
                      <div class="mb-4">
                        <label class="form-label" for="example-select">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" v-model="form.status">
                          <option value="active">Activo</option>
                          <option value="disabled">Deshabilitado</option>
                          <option value="suspended">Suspendido</option>
                        </select>
                      </div>
                    </div>
                  </div>


            </div>
          </BaseBlock>
        </div>

        <div class="col-sm-12 col-xl-12">
          <BaseBlock title="Permisos"  class="h-100 mb-0" content-class="fs-sm">
            <table class="table table-hover table-vcenter">
              <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Menu</th>
                <th></th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                <tr v-for="item in form.permissions">
                  <td class="text-center">
                    <input class="form-check-input" type="checkbox" v-model="item.selected">
                  </td>
                  <td class="fw-semibold fs-sm" style="width: 100px">{{ item.header }}</td>
                  <td class="fw-semibold fs-sm" style="width: 160px">{{ item.menu }}</td>
                  <td class="fw-semibold fs-sm">{{ item.option }}</td>
                </tr>
              </tbody>
            </table>
          </BaseBlock>
        </div>

        <div class="mb-4">
          <button type="submit" class="btn btn-success me-2">{{ user.id ? 'Actualizar' : 'Guardar'  }}</button>
          <button v-if="!user.id" type="button" @click="clearForm()" class="btn btn-light">Limpiar</button>
        </div>

      </div>
    </form>
  </div>
</template>

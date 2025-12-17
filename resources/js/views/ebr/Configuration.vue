<script setup lang="ts">
import { router, useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
  errors: Object,
  templates: {
    type: Object,
    default: () => ({
      clients: [],
      operations: [],
    }),
  },
  ebr_configuration: {
    type: Object,
    default: () => ({
      clients: [],
      operations: [],
    }),
  },
  risk_elements: Object,
  risk_elements_selected: Array,
  risk_indicators: Object,
  risk_indicators_selected: Array,
});


const form = useForm({
  template_clients_config: [] as string[],
  template_operations_config: [] as string[],
  risk_element_config: props.risk_elements_selected,
  risk_indicator_config: props.risk_indicators_selected
});

onMounted(() => {
  form.template_clients_config = [...(props.ebr_configuration.clients ?? [])];
  form.template_operations_config = [...(props.ebr_configuration.operations ?? [])];
});

function toTitleCase(str) {
  if (!str) return "";
  return str
    .toLowerCase()
    .replace(/\b\w/g, char => char.toUpperCase());
}

function submitConfigTemplate() {
  form.post("/ebr-configuration", {
    onSuccess: () => form.reset(),
  });
}

function submitRiskElementConfig() {
  form.post("/ebr-configuration-risk-element", {
    onSuccess: () => form.reset(),
  });
}

function submitRiskIndicatorConfig() {
  form.post("/ebr-configuration-risk-indicator", {
    onSuccess: () => form.reset(),
  });
}
</script>

<template>
  <Head title="EBR" />

  <BasePageHeading
    title="EBR"
    :subtitle="`Configuracion del EBR`"
  >
    <template #extra>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="/dashboard">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Configuracion del EBR
          </li>
        </ol>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row">
      <div class="col-12">
        <!-- Block Tabs Default Style -->
        <div class="block block-rounded">
          <ul class="nav nav-tabs nav-tabs-block" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="btabs-static-home-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-home" role="tab" aria-controls="btabs-static-home" aria-selected="true">Plantillas</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="btabs-static-profile-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-profile" role="tab" aria-controls="btabs-static-profile" aria-selected="false" tabindex="-1">Riesgos Inherentes</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="btabs-static-profile-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-risk_indicator" role="tab" aria-controls="btabs-static-profile" aria-selected="false" tabindex="-1">Indicadores de Riesgo</button>
            </li>
          </ul>
          <div class="block-content tab-content">
            <div class="tab-pane active" id="btabs-static-home" role="tabpanel" aria-labelledby="btabs-static-home-tab" tabindex="0">
              <div class="row">
                <div class="col-5">
                  <h4 class="fw-normal">Plantilla Clientes</h4>
                  <table class="table table-sm table-hover table-vcenter">
                    <thead>
                    <tr>
                      <th class="text-center" style="width: 50px;">#</th>
                      <th>Columna</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                      v-for="(column, index) in props.templates.clients"
                      :key="index"
                    >
                      <td class="text-center">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          :value="column"
                          v-model="form.template_clients_config"
                        />
                      </td>
                      <td class="fs-sm">{{ column }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>

                <div class="col-2"></div>

                <div class="col-5">
                  <h4 class="fw-normal">Plantilla Operaciones</h4>
                  <table class="table table-sm table-hover table-vcenter">
                    <thead>
                    <tr>
                      <th class="text-center" style="width: 50px;">#</th>
                      <th>Columna</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                      v-for="(column, index) in props.templates.operations"
                      :key="index"
                    >
                      <td class="text-center">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          :value="column"
                          v-model="form.template_operations_config"
                        />
                      </td>
                      <td class="fs-sm">{{ column }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="mb-4">
                  <button type="button" @click="submitConfigTemplate" class="btn btn-success me-2">Guardar</button>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="btabs-static-profile" role="tabpanel" aria-labelledby="btabs-static-profile-tab" tabindex="0">
              <table class="table table-hover table-sm table-vcenter">
                <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Elementos de Riesgo</th>
                  <th class="d-none d-sm-table-cell">Sub Encabezado</th>
                  <th class="d-none d-sm-table-cell">Encabezado Lateral</th>
                  <th class="d-none d-sm-table-cell">Descripcion</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(riskElement, indexRiskElement) in risk_elements" :key="indexRiskElement">
                  <th class="text-center" scope="row">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :value="riskElement.id"
                      v-model="form.risk_element_config"
                    />
                  </th>
                  <td class="fs-sm">
                    {{ toTitleCase(riskElement.risk_element) }}
                  </td>
                  <td class="fs-sm">
                    {{ toTitleCase(riskElement.sub_header) }}
                  </td>
                  <td class="fs-sm">
                    {{ riskElement.lateral_header }}
                  </td>
                  <td class="fs-sm">
                    {{ riskElement.description }}
                  </td>
                </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="mb-4">
                  <button type="button" @click="submitRiskElementConfig" class="btn btn-success me-2">Guardar</button>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="btabs-static-risk_indicator" role="tabpanel" aria-labelledby="btabs-static-profile-tab" tabindex="0">
              <table class="table table-hover table-sm table-vcenter">
                <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Caracteristica</th>
                  <th class="d-none d-sm-table-cell">Clave</th>
                  <th class="d-none d-sm-table-cell">Nombre</th>
                  <th class="d-none d-sm-table-cell">Descripción</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(riskIndicator, indexRiskIndicator) in risk_indicators" :key="indexRiskIndicator">
                  <th class="text-center" scope="row">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :value="riskIndicator.id"
                      v-model="form.risk_indicator_config"
                    />
                  </th>
                  <td class="fs-sm">
                    {{ toTitleCase(riskIndicator.characteristic) }}
                  </td>
                  <td class="fs-sm">
                    {{ toTitleCase(riskIndicator.key) }}
                  </td>
                  <td class="fs-sm">
                    {{ riskIndicator.name }}
                  </td>
                  <td class="fs-sm">
                    {{ riskIndicator.description }}
                  </td>
                </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="mb-4">
                  <button type="button" @click="submitRiskIndicatorConfig" class="btn btn-success me-2">Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

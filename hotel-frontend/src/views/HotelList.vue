<template>
  <div>
    <h1 class="titulo">Sistema hotelero</h1>

    <button class="btn-crear" @click="mostrarFormulario = true">Crear nuevo hotel</button>

    <!-- FORMULARIO HOTEL -->
    <HotelForm
      v-if="mostrarFormulario"
      :hotel="nuevoHotel"
      :editando="editando"
      @guardar="guardarHotel"
      @cancelar="cancelarFormulario"
    />

    <table class="datatable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Ciudad</th>
          <th>Dirección</th>
          <th>Número de habitaciones</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="hoteles.length">
          <template v-for="hotel in hoteles" :key="hotel.id">
            <!-- FILA PRINCIPAL DEL HOTEL -->
            <tr>
              <td>{{ hotel.nombre }}</td>
              <td>{{ hotel.ciudad }}</td>
              <td>{{ hotel.direccion }}</td>
              <td>{{ hotel.numero_habitaciones }}</td>
              <td>
                <button @click="editarHotel(hotel)">Editar Hotel</button>
                <button @click="seleccionarHotel(hotel)">Crear habitación</button>
                <button @click="mostrarHabitaciones(hotel.id)">Ver habitaciones</button>
              </td>
            </tr>

            <!-- FORMULARIO DE HABITACIÓN PARA ESE HOTEL -->
            <tr v-if="hotelSeleccionado?.id === hotel.id" :key="'form-' + hotel.id">
              <td colspan="5">
               <HabitacionForm
                :hotelId="hotel.id"
                :hotelNombre="hotel.nombre"
                :totalHabitacionesHotel="hotel.numero_habitaciones"
                :habitacionesExistentes="habitacionesPorHotel[hotel.id] || 0"
                :editando="editandoHabitacion"
                :habitacionEditada="habitacionSeleccionada"
                @guardado="onHabitacionGuardada"
                @cancelar="cancelarFormularioHabitacion"
              />
              </td>
            </tr>

            <!-- LISTADO DE HABITACIONES PARA ESE HOTEL -->
            <tr v-if="hotel.id === hotelSeleccionadoId" :key="'list-' + hotel.id">
              <td colspan="5">
                <div v-if="mensajeHabitaciones" class="mensaje-habitaciones">
                  {{ mensajeHabitaciones }}
                </div>
                <table v-else class="datatable">
                  <thead>
                    <tr>
                      <th>Tipo</th>
                      <th>Acomodación</th>
                      <th>Cantidad</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="habitacion in habitaciones" :key="habitacion.id">
                      <td>{{ habitacion.tipo?.nombre || 'Sin tipo' }}</td>
                      <td>{{ habitacion.acomodacion?.nombre || 'Sin acomodación' }}</td>
                      <td>{{ habitacion.cantidad }}</td>
                      <td>
                        <button @click="editarHabitacion(habitacion)">Editar</button>
                        <button @click="eliminarHabitacion(habitacion.id)">Eliminar</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </template>
        </template>

        <!-- MENSAJE SI NO HAY HOTELES -->
        <tr v-else>
          <td colspan="5">No hay hoteles registrados.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';

import HotelForm from '@/components/HotelForm.vue';
import HabitacionForm from '@/components/HabitacionForm.vue';

// Estado principal
const hoteles = ref([]);
const habitaciones = ref([]);
const habitacionesPorHotel = ref({});
const mostrarFormulario = ref(false);
const mostrarFormularioHabitacion = ref(false);
const editando = ref(false);
const mensajeHabitaciones = ref('');

const nuevoHotel = ref(inicializarHotel());
const hotelSeleccionado = ref(null);
const hotelSeleccionadoId = ref(null);
const errores = reactive({});

function limpiarErrores() {
  Object.keys(errores).forEach(key => delete errores[key]);
}

const habitacionSeleccionada = ref(null);
const editandoHabitacion = ref(false);

function editarHabitacion(habitacion) {
  resetearVistas();
  habitacionSeleccionada.value = { ...habitacion };
  editandoHabitacion.value = true;
  mostrarFormularioHabitacion.value = true;

  const hotel = hoteles.value.find(h => h.id === habitacion.hotel_id);
  if (hotel) hotelSeleccionado.value = hotel;
}

async function eliminarHabitacion(id) {
  if (!confirm('¿Está seguro de eliminar esta habitación?')) return;

  try {
    await axios.delete(`/api/habitaciones/${id}`);
    await mostrarHabitaciones(hotelSeleccionadoId.value);
  } catch (error) {
    console.error('Error eliminando habitación:', error);
  }
}

// Hooks
onMounted(() => {
  cargarHoteles();
});

// Funciones
function inicializarHotel() {
  return {
    id: null,
    nombre: '',
    direccion: '',
    ciudad: '',
    nit: '',
    numero_habitaciones: 1,
  };
}

async function cargarHoteles() {
  try {
    const res = await axios.get('/api/hoteles');
    hoteles.value = res.data;
  } catch (error) {
    console.error('Error al cargar hoteles:', error);
  }
}

async function guardarHotel() {
  limpiarErrores(); // limpia errores al inicio para evitar residuos previos

  try {
    if (editando.value && nuevoHotel.value.id) {
      await axios.put(`/api/hoteles/${nuevoHotel.value.id}`, nuevoHotel.value);
      alert('Hotel actualizado exitosamente.');
    } else {
      await axios.post('/api/hoteles', nuevoHotel.value);
      alert('Hotel creado exitosamente.');
    }

    cancelarFormulario();
    await cargarHoteles();
  } catch (error) {
    if (error.response?.status === 422) {
      Object.assign(errores, error.response.data.errors);

      const mensajes = Object.values(error.response.data.errors)
        .flat()
        .join('\n');

      alert('Errores de validación:\n' + mensajes);
      console.error('Errores de validación:', errores);
    } else {
      console.error('Error al guardar hotel:', error);
      alert('Ocurrió un error al guardar el hotel. Nombre Hotel y/o NIT ya existente');
    }
  }
}


function editarHotel(hotel) {
  resetearVistas();
  nuevoHotel.value = { ...hotel };
  mostrarFormulario.value = true;
  editando.value = true;
}

function cancelarFormulario() {
  resetearVistas();
  mostrarFormulario.value = false;
  editando.value = false;
  nuevoHotel.value = inicializarHotel();
}

function seleccionarHotel(hotel) {
  resetearVistas();
  habitacionSeleccionada.value = null;
  hotelSeleccionado.value = hotel;
  editandoHabitacion.value = false;
  habitacionSeleccionada.value = null;
  mostrarFormularioHabitacion.value = true;
}

function cancelarFormularioHabitacion() {
  mostrarFormularioHabitacion.value = false;
  editandoHabitacion.value = false;
  habitacionSeleccionada.value = null;
  hotelSeleccionado.value = null;
}

async function mostrarHabitaciones(hotelId) {
  resetearVistas();
  try {
    hotelSeleccionadoId.value = hotelId;
    const res = await axios.get(`/api/habitaciones?hotel_id=${hotelId}`);
    
    if (res.data && res.data.length > 0) {
      habitaciones.value = res.data;
      mensajeHabitaciones.value = '';
    } else {
      habitaciones.value = [];
      mensajeHabitaciones.value = 'No hay habitaciones registradas para este hotel.';
    }
  } catch (error) {
    console.error('Error al mostrar habitaciones:', error);
    habitaciones.value = [];
    mensajeHabitaciones.value = 'Error al cargar habitaciones.';
  }
}


async function onHabitacionGuardada() {
  resetearVistas();
  cancelarFormularioHabitacion();
}

function resetearVistas() {
  mostrarFormulario.value = false;
  mostrarFormularioHabitacion.value = false;
  nuevoHotel.value = inicializarHotel();
  editando.value = false;

  habitaciones.value = [];
  mensajeHabitaciones.value = '';
  habitacionSeleccionada.value = null;
  editandoHabitacion.value = false;
}
</script>

<style scoped>
.titulo {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 16px;
}

.btn-crear {
  background-color: #2a50a1;
  color: white;
  padding: 8px 16px;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s ease;
  margin-bottom: 20px;
}

.btn-crear:hover {
  background-color: #344b98;
}
</style>

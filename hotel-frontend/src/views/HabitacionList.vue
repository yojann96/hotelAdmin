<template>
  <div>
    <h2>Habitaciones</h2>

    <ul>
      <li v-for="habitacion in habitaciones" :key="habitacion.id">
        {{ habitacion.tipo?.nombre || 'Sin tipo' }} - 
        {{ habitacion.acomodacion?.nombre || 'Sin acomodación' }} - 
        Cantidad: {{ habitacion.cantidad }}

        <button @click="editarHabitacion(habitacion)">Editar</button>
        <button @click="eliminarHabitacion(habitacion.id)">Eliminar</button>
      </li>
    </ul>

    <button v-if="!mostrarFormulario" @click="mostrarFormulario = true">
      Agregar habitación
    </button>

    <HabitacionForm
      v-if="mostrarFormulario"
      :hotelId="hotelId"
      :habitacion="habitacionSeleccionada"
      :editando="editando"
      :totalHabitacionesHotel="totalHabitacionesHotel"
      :hotelNombre="hotelNombre"
      :habitacionesExistentes="habitaciones.reduce((acc, h) => acc + h.cantidad, 0)"
      @cancelar="cancelarFormulario"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import HabitacionForm from '@/components/HabitacionForm.vue';

const props = defineProps({
  hotelId: { type: Number, required: true },
  hotelNombre: { type: String, default: '' },
  totalHabitacionesHotel: { type: Number, required: true },
});

const habitaciones = ref([]);
const mostrarFormulario = ref(false);
const habitacionSeleccionada = ref(null);
const editando = ref(false);

const cargarHabitaciones = async () => {
  try {
    const res = await axios.get('/api/habitaciones?hotel_id=' + props.hotelId);
    habitaciones.value = res.data;
  } catch (error) {
    console.error('Error cargando habitaciones:', error);
  }
};

const editarHabitacion = (habitacion) => {
  habitacionSeleccionada.value = { ...habitacion };
  editando.value = true;
  mostrarFormulario.value = true;
};

const eliminarHabitacion = async (id) => {
  if (!confirm('¿Está seguro de eliminar esta habitación?...')) return;
  try {
    await axios.delete(`/api/habitaciones/${id}`);
    await cargarHabitaciones();
  } catch (error) {
    console.error('Error eliminando habitación:', error);
  }
};

const cancelarFormulario = () => {
  habitacionSeleccionada.value = null;
  editando.value = false;
  mostrarFormulario.value = false;
};


onMounted(cargarHabitaciones);
</script>

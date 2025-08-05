<template>
  <div class="habitacion-form">
    <h3>{{ editando ? 'Editar' : 'Crear' }} Habitaciones para {{ hotelNombre }}</h3>

    <form @submit.prevent="guardarHabitacion">
      <label for="cantidad">Número de Habitaciones:</label>
      <input
        id="cantidad"
        type="number"
        v-model.number="habitacion.cantidad"
        :min="1"
        :max="habitacionesDisponibles"
        required
        placeholder="Cantidad"
      />

      <div>
        <label for="tipo">Tipo de habitación:</label>
        <select v-model="habitacion.tipo_habitacion_id" required>
          <option value="">Seleccione</option>
          <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
            {{ tipo.nombre }}
          </option>
        </select>
      </div>

      <div>
        <label for="acomodacion">Acomodación:</label>
        <select v-model="habitacion.acomodacion_id" required :disabled="!acomodaciones.length">
          <option value="">Seleccione</option>
          <option v-for="ac in acomodaciones" :key="ac.id" :value="ac.id">
            {{ ac.nombre }}
          </option>
        </select>
      </div>

      <p>
        Total habitaciones disponibles para {{ editando ? 'editar' : 'crear' }}:
        {{ habitacionesDisponibles }}
      </p>

      <div class="buttons">
        <button type="submit" :disabled="!puedeGuardar">{{ editando ? 'Actualizar' : 'Guardar' }}</button>
        <button type="button" @click="$emit('cancelar')">Cancelar</button>
      </div>

      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  hotelId: Number,
  hotelNombre: String,
  totalHabitacionesHotel: Number,
  habitacionesExistentes: Number,
  editando: Boolean,
  habitacionEditada: Object
});

const emit = defineEmits(['guardado', 'cancelar']);

const tipos = ref([]);
const acomodaciones = ref([]);
const error = ref('');

const habitacion = ref({
  tipo_habitacion_id: '',
  acomodacion_id: '',
  cantidad: 1,
});

const habitacionesExistentesLocal = ref(props.habitacionesExistentes || 0);

onMounted(async () => {
  try {
    const res = await axios.get('/api/tipo-habitaciones');
    tipos.value = res.data;
    await cargarHabitacionesExistentes();
  } catch (e) {
    error.value = 'Error cargando tipos de habitación.';
    console.error(e);
  }

  // Si está en modo edición, cargar los datos en el formulario
  if (props.editando && props.habitacionEditada) {
    habitacion.value = {
      tipo_habitacion_id: props.habitacionEditada.tipo_habitacion_id,
      acomodacion_id: props.habitacionEditada.acomodacion_id,
      cantidad: props.habitacionEditada.cantidad,
    };

    // Cargar acomodaciones de ese tipo para mostrar en el select
    try {
      const res = await axios.get(`/api/acomodaciones/por-tipo/${props.habitacionEditada.tipo_habitacion_id}`);
      acomodaciones.value = res.data;
    } catch (e) {
      console.error('Error cargando acomodaciones en edición', e);
    }
  }
});


// Cargar acomodaciones cuando cambia el tipo
watch(() => habitacion.value.tipo_habitacion_id, async (nuevoTipoId) => {
  if (!nuevoTipoId) {
    acomodaciones.value = [];
    habitacion.value.acomodacion_id = '';
    return;
  }

  try {
    const res = await axios.get(`/api/acomodaciones/por-tipo/${nuevoTipoId}`);
    acomodaciones.value = res.data;
    habitacion.value.acomodacion_id = '';
  } catch (e) {
    error.value = 'Error cargando acomodaciones.';
    console.error(e);
  }
});

watch(() => props.hotelId, () => {
  habitacionesExistentesLocal.value = props.habitacionesExistentes || 0;
  cargarHabitacionesExistentes();
});

// Calcular habitaciones disponibles
const habitacionesDisponibles = computed(() => {
  const disponibles = props.totalHabitacionesHotel - habitacionesExistentesLocal.value;
  return disponibles > 0 ? disponibles : 0;
});

// Validación para habilitar guardar
const puedeGuardar = computed(() =>
  habitacion.value.cantidad > 0 &&
  habitacion.value.cantidad <= habitacionesDisponibles.value &&
  habitacion.value.tipo_habitacion_id !== '' &&
  habitacion.value.acomodacion_id !== ''
);

async function cargarHabitacionesExistentes() {
  try {
    const res = await axios.get(`/api/habitaciones?hotel_id=${props.hotelId}`);
    let total = 0;
    res.data.forEach(h => {
      total += h.cantidad;
    });
    habitacionesExistentesLocal.value = total;
  } catch (e) {
    error.value = 'Error cargando habitaciones existentes.';
    console.error(e);
  }
}

const guardarHabitacion = async () => {
  if (!puedeGuardar.value) {
    error.value = 'Complete todos los campos y no exceda el total permitido.';
    return;
  }

  try {
    if (props.editando) {
      await axios.put(`/api/habitaciones/${props.habitacionEditada.id}`, {
        ...habitacion.value,
        hotel_id: props.hotelId,
      });
    } else {
      // POST para crear
      await axios.post('/api/habitaciones', {
        ...habitacion.value,
        hotel_id: props.hotelId,
      });
    }

    emit('guardado');

    // Sólo limpia el formulario si fue una creación
    if (!props.editando) {
      habitacion.value = {
        tipo_habitacion_id: '',
        acomodacion_id: '',
        cantidad: 1,
      };
      acomodaciones.value = [];
    }

    error.value = '';
    await cargarHabitacionesExistentes();

  } catch (e) {
    const mensaje = e.response?.data?.message || 'Error guardando habitación.';
    error.value = mensaje;
    console.error(mensaje);
  }
};

watch(() => props.editando, (nuevoValor) => {
  if (!nuevoValor) {
    habitacion.value = {
      tipo_habitacion_id: '',
      acomodacion_id: '',
      cantidad: 1,
    };
    acomodaciones.value = [];
  }
});

</script>

<style scoped>
/* Igual que tu versión anterior */
.habitacion-form {
  max-width: 500px;
  padding: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #f9f9f9;
  margin-top: 20px;
}
label {
  display: block;
  font-weight: 600;
  margin-top: 12px;
}
input,
select {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border-radius: 4px;
  border: 1px solid #ccc;
}
.buttons {
  margin-top: 16px;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}
button {
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:disabled {
  background: #ccc;
  cursor: not-allowed;
}
.error {
  color: red;
  margin-top: 12px;
}
</style>

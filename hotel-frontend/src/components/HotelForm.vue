<template>
  <div class="hotel-form">
    <h2>{{ editando ? 'Editar Hotel' : 'Registrar Nuevo Hotel' }}</h2>
    
    <form @submit.prevent="submit">
      <div class="form-grid">
        <div class="form-field">
          <label for="nombre">Nombre</label>
          <input id="nombre" v-model="hotel.nombre" type="text" placeholder="Ej: Hotel El Bosque" required />
        </div>

        <div class="form-field">
          <label for="direccion">Dirección</label>
          <input id="direccion" v-model="hotel.direccion" type="text" placeholder="Cra 45 #12-34" required />
        </div>

        <div class="form-field">
          <label for="ciudad">Ciudad</label>
          <input id="ciudad" v-model="hotel.ciudad" type="text" placeholder="Bogotá" required />
        </div>

        <div class="form-field">
          <label for="nit">NIT</label>
          <input id="nit" v-model="hotel.nit" type="text" placeholder="900123456-7" required />
        </div>

        <div class="form-field full-width">
          <label for="numero_habitaciones">Número de Habitaciones</label>
          <input
            id="numero_habitaciones"
            v-model.number="hotel.numero_habitaciones"
            type="number"
            placeholder="Ej: 50"
            min="1"
            required
          />
        </div>
      </div>
      <p v-if="error" class="text-red-600">{{ error }}</p>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          {{ editando ? 'Actualizar' : 'Guardar' }}
        </button>
        <button type="button" class="btn btn-secondary" @click="$emit('cancelar')">
          Cancelar
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
defineProps(['hotel', 'editando']);
const emit = defineEmits(['guardar', 'cancelar']);

const submit = () => {
  emit('guardar');
};
</script>

<style scoped>
.hotel-form {
  background: #f8f9fa;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #ddd;
  max-width: 700px;
  margin: 0 auto;
}

h2 {
  margin-bottom: 20px;
  font-size: 1.5rem;
  color: #333;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 16px;
  margin-bottom: 20px;
}

.form-field {
  display: flex;
  flex-direction: column;
}

.form-field label {
  font-weight: 600;
  margin-bottom: 6px;
}

.form-field input {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.full-width {
  grid-column: span 2;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn {
  padding: 10px 18px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  font-size: 1rem;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
}
</style>

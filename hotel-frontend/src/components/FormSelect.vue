<template>
  <div class="form-select">
    <label :for="id">{{ label }}</label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      required
    >
      <option disabled value="">Seleccione una opción</option>
      <option
        v-for="option in options"
        :key="option.id"
        :value="option.id"
      >
        {{ option.nombre }}
      </option>
    </select>
  </div>
</template>

<script setup>
defineProps({
  id: String,
  label: String,
  options: Array,
  modelValue: [String, Number]
})

defineEmits(['update:modelValue'])
</script>

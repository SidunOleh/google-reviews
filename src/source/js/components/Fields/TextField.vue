<template>
    <div class="field">
        <label :for="id">
            {{ label }}
        </label>
        <input 
            type="text" 
            :id="id"
            :value="modelValue"
            @input="updateValue">
    </div>
</template>

<script>
export default {
    props: [
        'modelValue', 
        'label', 
        'id',
    ],
    methods: {
        updateValue(e) {
            this.$emit('update:modelValue', e.target.value)
            this.$emit('input')
        },
    },
}
</script>

<style scoped>
.field {
    padding: 5px 10px;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    color: black;

}
label {
    margin-bottom: 5px;
}
</style>
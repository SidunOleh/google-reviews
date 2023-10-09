<template>
    <div class="field">
        <label :for="id">
            {{ label }}
        </label>
        <input 
            type="file" 
            :id="id"
            :accept="accept"
            @change="$emit('update-file', $event.target.files[0])">
    </div>
</template>

<script>
export default {
    props: [
        'label', 
        'id',
        'accept',
    ]
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
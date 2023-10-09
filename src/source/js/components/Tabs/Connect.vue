<template>
    <div class="google">
        <div class="btn" @click="redirectUrl">
            Connect to Google
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    methods: {
        redirectUrl() {
            this.$emit('loading', true)
            axios.get('/wp-admin/admin-ajax.php', {
                params: {
                    action: 'auth_redirect',
                },
            }).then(res => {
                location.href = res.data.redirectUrl
            }).catch(err => {
                this.$emit('loading', false)
            })
        }
    },
}
</script>

<style>
.btn {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 5px;
    background: #343a40;
    cursor: pointer;
    color: white;
    font-weight: 600;
}
.btn:hover {
    background-color: #000000;
}
</style>
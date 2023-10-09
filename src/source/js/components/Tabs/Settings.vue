<template>
    <div class="settings">

        <TextField 
            label="Client ID"
            id="client_id"
            v-model="settings.client_id"
            @input="saveSetting('client_id')"/>

        <TextField 
            label="Client Secret"
            id="client_secret"
            v-model="settings.client_secret"
            @input="saveSetting('client_secret')"/>

        <TextField 
            label="Redirect URI"
            id="redirect_uri"
            accept="application/JSON"
            v-model="settings.redirect_uri"
            @input="saveSetting('redirect_uri')"/>

        <FileField
            label="Set client secrets from file"
            accept="application/JSON"
            id="client_secrets_file"
            @update-file="uploadClientSecrets"/>

    </div>
</template>

<script>
import axios from 'axios'
import TextField from '../Fields/TextField.vue'
import FileField from '../Fields/FileField.vue'
export default {
    components: {
        TextField,
        FileField,
    },
    data() {
        return {
            settings: {
                client_id: '',
                client_secret: '',
                redirect_uri: '',
            }
        }
    },
    methods: {
        getSettings() {
            this.$emit('loading', true)
            axios.get('/wp-admin/admin-ajax.php?action=get_settings')
                .then(res => {
                    this.settings = res.data.settings
                    this.$emit('loading', false)
                }).catch(err => {
                    this.$emit('loading', false)
                })
        },
        saveSetting(name) {
            axios.post('/wp-admin/admin-ajax.php', {
                action: 'save_setting',
                name,
                value: this.settings[name],
            },{
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
        },
        uploadClientSecrets(file) {
            this.$emit('loading', true)
            axios.post('/wp-admin/admin-ajax.php', {
                action: 'upload_client_secrets',
                client_secrets: file,
            }, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }).then(res => {
                this.getSettings()
            }).catch(err => {
                alert(err.response.data.error)
                this.$emit('loading', false)
            })
        },
    },
    mounted() {
        this.getSettings()
    },
    unmounted() {
        this.$emit('loading', false)
    }
}
</script>

<style scoped>
.settings {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
</style>
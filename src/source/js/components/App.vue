<template>
    <div class="tabs">
        <div 
            class="tab" 
            v-for="tab in tabs" 
            @click="currentTab = tab" 
            :class="{current: currentTab == tab}">
            {{ tab }}
        </div>
    </div>
    <div class="body" ref="body">
        <div class="content">
            <component :is="currentTabComponent" @loading="loading">
            </component>
        </div>
    </div>
</template>

<script>
import Reviews from './Tabs/Reviews.vue'
import Connect from './Tabs/Connect.vue'
import Settings from './Tabs/Settings.vue'
export default {
    components: {
        Reviews,
        Connect,
        Settings,
    },
    data() {
        return {
            tabs: [ 
                'Reviews', 
                'Connect',
                'Settings',
            ],
            currentTab: 'Reviews',
        }
    },
    computed: {
        currentTabComponent() {
            return this.currentTab
        },
    },
    methods: {
        loading(bool) {
            if (bool) {
                this.$refs.body.classList.add('loading')
            } else {
                this.$refs.body.classList.remove('loading')
            }
        },
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search)
        if (urlParams.get('connected') == 'true') {
            setTimeout(() => alert('Connected successfully.'), 1000)
        } 
        if (urlParams.get('connected') == 'false') {
            setTimeout(() => alert('Connection failed.'), 1000)
        }
        urlParams.delete('connected')
        const newUrl = window.location.pathname + '?' + urlParams.toString()
        window.history.replaceState(null, '', newUrl)
    }
}
</script>

<style scoped>
* {
    font-size: 15px;
}
.tabs {
    display: flex;
    border-bottom: 1px solid #343a4017;
}
@media(max-width: 425px) {
    .tabs {
        flex-direction: column;
    }
}
.tab {
    flex-grow: 1;
    padding: 10px 30px;
    cursor: pointer;
    font-size: 18px;
    background-color: white;
    font-weight: 600;
    color: black;
}
.tab:hover {
    background-color: #343a40;
    color: white;
}
.tab.current {
    background-color: #343a40;
    color: white;
}
.body {
    padding: 10px 20px;
    background-color: white;
}
.loading {
    position: relative;
}

.loading::before {
    content: "";
    position: absolute;
    z-index: 10;
    top: 0;
    left: 0;
    background: -webkit-gradient(linear, left top, right bottom, color-stop(40%, #eeeeee), color-stop(50%, #dddddd), color-stop(60%, #eeeeee));
    background: linear-gradient(to bottom right, #eeeeee 40%, #dddddd 50%, #eeeeee 60%);
    background-size: 200% 200%;
    background-repeat: no-repeat;
    -webkit-animation: placeholderShimmer 2s infinite linear;
    animation: placeholderShimmer 2s infinite linear;
    height: 100%;
    width: 100%;
    opacity: 0.6;
}

@-webkit-keyframes placeholderShimmer {
    0% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0 0;
    }
}

@keyframes placeholderShimmer {
    0% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0 0;
    }
}
</style>
<template>
    <div>
        <nav class="nav has-shadow">
            <div class="nav-left">
                <a class="nav-item">
                    <img src="http://bulma.io/images/bulma-logo.png" alt="Bulma logo">
                </a>
            </div>

            <span class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <div class="nav-right nav-menu is-hidden-tablet">
                <slot name="hidden"></slot>                
            </div>
            <div class="nav-right nav-menu">
                <ui-progress-circular color="multi-color" v-show="loading"></ui-progress-circular>
                <slot name="normal"></slot>
            </div>            
        </nav>
        <ui-preloader :show="loading"></ui-preloader>
    </div>
</template>
<script>
    export default {        
        data() { 
            return { 
                loading:false
            }
        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                vm.loading = true
                setTimeout(
                    () => {
                        vm.loading = false
                    }, 500)
            })
        },
        watch: {
            $route () {
                this.loading = true
                setTimeout(
                    () => {
                        this.loading = false
                }, 500)
            }
      }
    }
</script>
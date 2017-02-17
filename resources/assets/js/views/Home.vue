<template>
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Dashboard</div>

                        <div class="panel-body">
                            You are logged in!
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</template>

<script>
import Vodal from 'vodal'
    export default {
        components: { Vodal },
        data() {
            return {
                show: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
        }
    }
</script>

<style lang="scss">
@import "~vodal/flip.css";
@import "~vodal/door.css";
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Home Page</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
        <vodal :show="show" animation="door" @hide="show = false">
            <div>A vue modal with animations.</div>
        </vodal>
        <el-button type="primary" @click="show=true">Default Button</el-button>
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
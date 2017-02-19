<template>
    <div class="row">
        <el-upload v-if="!show"
            action="api/delito/preview"
            :headers="headers"
            type="drag"
            :on-success="handleSuccess"
            :on-error="handleError"
            :default-file-list="fileList"
            accept=".xlsx,.xls,.csv"
        >
            <i class="el-icon-upload"></i>
            <div class="el-dragger__text">Arrastra el archivo aqui o <em>click para importar</em></div>
            <div class="el-upload__tip" slot="tip">Solo archivos xls/xlsx/csv.</div>
            <div class="el-upload__tip" slot="tip" v-if="error">{{ message[0] }}</div>
        </el-upload>
        <div v-if="show">
            <nav class="nav">
                <div class="nav-left">
                    <ui-icon-button
                        color="primary"
                        icon="save"
                        size="large"
                        tooltip="Guardar"
                        tooltipPosition="top center"
                        @click="importar">                            
                    </ui-icon-button>
                </div>
                <div class="nav-right">
                    <ui-icon-button
                        color="red"
                        icon="cancel"
                        size="large"
                        tooltip="Cancelar"
                        tooltipPosition="top center"
                        @click="cancel">                            
                    </ui-icon-button>
                </div>
            </nav>
            <handsontable :data="delitos" :settings="settings"></handsontable>
        </div>  
    </div>
</template>

<script>
import Handsontable from '../components/Handsontable.vue'

export default {
    components: {
      Handsontable
    },

    data () {
        return {
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken,'X-Requested-With': 'XMLHttpRequest'},
            show: false,
            delitos: [],
            error: false,
            message: '',
            fileList: [],
            columns: [
                {
                    data: 'articulo',
                    type: 'numeric',
                    width: 100
                },
                {
                    data: 'inciso',
                    type: 'text',
                    width: 150
                },
                {
                    data: 'nombre',
                    type: 'text',
                    width: 500
                }
            ],
            colHeaders: [
                'Artículo',
                'Inciso',
                'Nombre'
            ],
            width: 850,
            height: 500,
            rowHeaders: true,
            columnSorting: true,
            sortIndicator: true,
            contextMenu: true,
            settings: {}
        }
    },
    methods: {
        handleSuccess(response, file, fileList) {
            this.show = true
            this.delitos = response.delitos
            this.settings.data = this.delitos
            this.settings.columns = this.columns
            this.settings.width = this.width
            this.settings.height = this.height
            this.settings.rowHeaders = this.rowHeaders
            this.settings.colHeaders = this.colHeaders
            this.settings.columnSorting = this.columnSorting
            this.settings.sortIndicator = this.sortIndicator
            this.settings.contextMenu = this.contextMenu
            this.settings.search = this.search
        },
        handleError(err, response, file) {
            this.$message.error('Oops, ocurrió un error. ' + response.errors.file);
            this.error = true
            this.message = response.errors.file
        },
        importar() {
        axios.post('api/delito/importar', this.delitos)
            .then( response => {
                this.$message({
                    message: 'Los registros se importaron correctamente.',
                    type: 'success'
                });
                this.$router.push('/delitos')
            }).catch( error => {
                this.$message({
                message: 'Corrija los errores.',
                type: 'error'
                });
                this.errors = error.response.data.errors
            })
        },
        cancel() {
            this.show = false
            this.delitos = []
            this.fileList = []
            this.error = ''
        }
    }
}
</script>
<style lang="scss">
@import '~bulma/sass/utilities/variables';
@import '~bulma/sass/utilities/mixins';

.app-main {
  padding-top: 60px;
  margin-left: 180px;
  transform: translate3d(0, 0, 0);

  @include mobile() {
    margin-left: 0;
  }

}

.app-content {
  padding: 20px;
}

.table-responsive {
  display: block;
  width: 100%;
  min-height: .01%;
  overflow-x: auto;
}
</style>
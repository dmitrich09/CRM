<template>
  <section>
      <div class="row justify-content-center">
          <p style="font-size:24px">Воронка</p>
      </div>  
      <div class="form-row">
          <div class="col">
              От<date-picker  v-model="form.from" valueType="format" ></date-picker> 
          </div>
          <div class="col">
              До<date-picker  v-model="form.to" valueType="format" ></date-picker> 
          </div>
          <div>
              Год<input type="checkbox" id="checkbox" v-model="form.group" style=" transform: scale(1.5); margin-left:10px">
          </div>
          <div class="col">
              <button class="btn btn-primary" @click="saveData() " v-if="!showForm">Добавить</button>
          </div>
          <div class="col-2"></div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
     
      <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th>Показатель</th>
            <th>Звонки</th>
            <th>ИТОГО</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id">
            <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
                  <td>{{model.planned_date}}</td>
                  <td>{{model.calls}}</td>
                  <td>{{model.leads}}</td>
            </tr>
                <tr v-if="edit != null && edit.id == model.id">
                    <td><input type="text" class="form-control" v-model="edit.id" placeholder="Название"></td>
                    <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название"></td>  
                <td><a class="fui-check" @click="update"></a></td>
                  <td><a class="fui-cross" @click="edit = null"></a></td>
                </tr>
        </tbody>

      </table>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  //import vueConfirmationButton from '../../utils/confirmButton'
  import pagination from "../../utils/pagination"
   import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'

  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        form:{
          name: 'Hoвая',
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      //VueConfirmationButton: vueConfirmationButton,
      pagination,
      DatePicker
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'plan/datalist',
        source: 'source/datalist',
        pagination: 'plan/pagination',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'plan/save',
        find: 'plan/find',
        drop: 'plan/delete',
        findSource: 'source/find'
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.findData()
                })
      },
      update() {
        this.setError(null)
        this.save(this.edit)
                .then(() =>{
                  this.edit = null
                  this.findData()
                })
      },
      deleteData(id){
        this.setError(null)
        this.drop(id)
                .then(() =>{
                  this.findData()
                })
      },
      toChange(model){
        this.edit = Object.assign({}, model)
      },
      setPage(i){
        this.page = i
        this.findData()
      },
      findData(){
        this.find({page:this.page, query: this.search})
      }
    },
    created() {
      this.setHeader('План')
      this.page = this.pagination.page
      this.findData()
      this.findSource()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>

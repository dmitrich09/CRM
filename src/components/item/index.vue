<template>
  <section>
      <div class="form-row">
          <div class="col-8"></div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover">  
          <tr>
              <th>Документ</th>
              <th>Название</th>
              <th>Тип</th>
              <th>Срок оформления</th>
              <th>Количество</th>
              <th>Стоимость</th>
              <th>Поставщик</th>
              <th>Скидка</th>
              <th>Итого</th>
              <th>Себестоимость</th>
              <th>ПИ на руки</th>
              <th>Инсп. контроль</th>
              <th></th>
              <th></th>
              <th></th>
          </tr>
          <tbody v-for="model in datalist" :key="model.id">
              <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
                  <td>{{getDocumentName(model.document_id)}}</td>
                  <td>{{model.nameproduct}}</td>
                  <td>{{model.typemarkmodel}}</td>
                  <td>{{ getTimelineStatus(model.timeline)? getTimelineStatus(model.timeline) : 'не задано' }}</td>
                  <td>{{model.quantity}}</td>
                  <td>{{ model.cost? model.cost : 'не задано'}}</td>
                  <td >{{ model.provider_id? model.provider_id : 'не задано' }}</td>
                  <td>{{model.discount}}</td>
                  <td>{{model.total}}</td>
                  <td>{{ model.our_cost? model.our_cost : 'не задано' }}</td>
                  <td>{{getyesNoList(model.pionhand)}}</td>
                  <td>{{getyesNoList(model.control)}}</td>
                  <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>
                  <td width="60px;"><vue-confirmation-button
                          v-if="model.deleted_at == null || model.deleted_at == ''"
                          :css="'fui-trash'"
                          v-on:confirmation-success="deleteData(model.id)">
                    </vue-confirmation-button>
                  </td>
              </tr>
              <tr v-if="edit != null && edit.id == model.id">
                   <td>
                        <select class="select"  v-model="edit.document_id" >
                            <option  v-for="document in documents" :key="document.id" :value="document.id" >{{document.name}}</option>
                        </select>
                  </td>
                  <td><input type="text" class="form-control" v-model="edit.nameproduct" placeholder="Название"></td>  
                  <td><input type="text" class="form-control" v-model="edit.typemarkmodel" placeholder="Тип"></td>  
                  <td><input type="text" class="form-control" disabled></td>  
                  <td><input type="text" class="form-control" v-model="edit.quantity" placeholder="Количество"></td>  
                  <td><input type="text" class="form-control" v-model="edit.cost" placeholder="Стоимость"></td>  
                  <td>
                        <select class="select"  v-model="edit.provider_id" >
                            <option  v-for="provider in providers" :key="provider.id" :value="provider.id" >{{provider.name}}</option>
                        </select>
                  </td>
                  <td><input type="text" class="form-control" v-model="edit.discount" placeholder="Скидка"></td>  
                  <td><input type="text" class="form-control" v-model="edit.our_cost" placeholder="Себестоимость ед"></td>  
                  <td><input type="text" class="form-control" v-model="edit.control" placeholder="Инспекционный контроль"></td> 
                     <td>
                        <select class="select" style="width:100px!imortant!" v-model="edit.pionhand" >
                            <option  value="10">ДА</option>
                            <option  value="20">НЕТ</option>
                        </select>
                  </td>
                     <td>
                         <select class="select" style="width:100px!imortant!" v-model="edit.control" >
                            <option  value="10">ДА</option>
                            <option  value="20">НЕТ</option>
                        </select>
                  </td> 
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
  import vueConfirmationButton from '../../utils/confirmButton'
  import pagination from "../../utils/pagination"

  export default {
    mixins: [Acl],
    name: 'item',
    props:['application_id'],
    data () {
      return {
        form:{
          name: 'Hoвая',
        },
        search: null,
        page: null,
        edit: null,
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination
    },
    computed: {
      ...Vuex.mapGetters({   
        apiUrl: 'app/apiUrl', 
        pagination: 'item/pagination', 
        datalist: 'item/datalist',
        documents: 'document/datalist',
        timeline: 'dictionary/timelineStatus',  
        yesNo: 'dictionary/yesNoList',
        providers: 'provider/datalist',
     
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'item/save',
        find: 'item/find',
        drop: 'item/delete',
        findAllDocument: 'document/findAll',
        findAllProviders: 'provider/findAll',
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
        this.find({page:this.page, query: this.search, application_id:this.application_id})  
    },
    getDocumentName(id){
        var result = {}
        this.documents.map((el) => {
            if(id == el.id){
            result = el.name
            }
        })
        return result
        },
    getTimelineStatus(id){
        var result = {}
        this.timeline.map((el) => {
            if(id == el.id){
            result = el.name
            }
        })
        return result
    },
    getyesNoList(id){
        var result = {}
        this.yesNo.map((el) => {
            if(id == el.id){
            result = el.name
            }
        })
        return result
    },
    },
 
    created() {
      this.setHeader('Элемент')
      this.page = this.pagination.page   
      this.findData()
      this.findAllDocument()
      this.findAllProviders()
    }
  }
</script>  
<style>
  .disabled {
    color:lightgrey;
  }
</style>

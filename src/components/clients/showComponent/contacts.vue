<template>
  <section>
      <div>
        <button class="btn btn-primary" @click="showForm = !showForm" > Добавить контакт</button>
      </div> 
       <div class="form-row"  v-if="showForm" style="margin: 10px 0 10px 0;">
            <div class="col-2"  style="text-align: left;">
              <div style=" font-size: 70%;">Имя</div> 
               <input type="text" class="form-control" v-model="form.name" >  
           </div>
           <div class="col-2"  style="text-align: left;">
              <div style=" font-size: 70%;">Тип контакта</div> 
                 <select class="select"  v-model="form.contacttype_id"  style=" width: 200px!important;">
                    <option  v-for="(data, id) in dataContractTypes" :key="id"  v-bind:value="data.id" >{{data.name}}</option>
                </select>
          </div>
           <div class="col-2"  style="text-align: left;">
              <div style=" font-size: 70%;">Телефон</div> 
               <input type="text" class="form-control" v-model="form.phone" > 
          </div>
          <div class="col-2"  style="text-align: left;">
              <div style=" font-size: 70%;">Email</div> 
               <input type="text" class="form-control" v-model="form.email" > 
          </div>
          <div class="col-2"  style="text-align: left;">
              <div style=" font-size: 70%;">Skype</div> 
               <input type="text" class="form-control" v-model="form.skype" > 
          </div>
          <div class="col-2">
              <div style=" font-size: 70%;">Комментарий</div> 
              <textarea type="text" class="form-control" v-model="form.comment" ></textarea>
          </div>
          <div class="col-2 " style="margin: auto" >
              <button class="btn btn-success"  @click="saveData()">Сохранить</button>   
          </div>
           <div class="col-2">
               <button class="btn btn-warning"  @click="form = {};showForm=false ">Отменить</button> 
          </div>
    </div>
     <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>Имя</th> 
            <th>Телефон</th>
            <th>Тип контакта</th>  
            <th>Skype</th>
            <th>Email</th>
            <th>Комментарий</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td>{{model.name}}</td>
            <td>{{model.phone}}</td>
            <td>{{getContactType(model.contacttype_id).name}} </td>
            <td>{{model.skype}}</td>
            <td>{{model.email}}</td>
            <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i class="fas fa-comment"></i></a></td> 
            <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" title="Редактировать"></a></td>     
            <td>
              <a href="#"><img src="../../../../static/images/iconsColors/video.svg" title="" style="max-width:20px"></a>
            </td>
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.name" placeholder="Поставлена"></td>
               <td><input type="text" class="form-control" v-model="edit.phone" placeholder=""></td>
              <td> 
                <select class="select"  v-model="edit.contacttype_id"  style=" width: 200px!important;">
                    <option  v-for="(data, id) in dataContractTypes" :key="id"  v-bind:value="data.id" >{{data.name}}</option>
                </select>
              </td>
              <td><input type="text" class="form-control" v-model="edit.skype" placeholder="Skype"></td>
              <td><input type="text" class="form-control" v-model="edit.email" placeholder="Email"></td>
              <td><input type="text" class="form-control" v-model="edit.comment" placeholder="Комментарий "></td>
              <td><a class="fui-check" @click="update"></a></td>
              <td><a class="fui-cross" @click="edit = null"></a></td>
          </tr>
        </tbody>
      </table>
  </section>
</template>

<script>
  import Acl from '../../../utils/acl'
  import Vuex from 'vuex'
  import vueConfirmationButton from '../../../utils/confirmButton'             
  // import pagination from "../../../utils/pagination"

  export default {
    mixins: [Acl],
    name: 'contacts',
    props: ['clients_id'],
    data () {
      return {
        form:{
          clients_id: '',
          contacttype_id:'',
          name:'',
          email:'',
          phone:'',
          skype:''  
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      // pagination,
      vueConfirmationButton
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'contacts/datalist',
        dataContractTypes: 'contacttype/datalist',
        pagination: 'contacts/pagination',
        callsStatus: 'dictionary/callsStatus',
        storeForm: 'mybuisness/form',
        user: 'auth/user',  
      }),  

    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'contacts/save',
        find: 'contacts/find',
        drop: 'contacts/delete',
        findContactsType: 'contacttype/find'
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.findData(this.storeForm)
                  this.form = {}
                  this.showForm=false
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
        this.find(this.storeForm)
      },
      getContactType(id){
          var result = {}
          this.dataContractTypes.map((el) => {
            if(id == el.id){
              result = el
            }
          })
          return result
        },
      toComments(id){
          this.$store.commit('comments/setValComment', {id:id}, {root: true})
      },
      getClientsId(){
        var obj = this.clients_id
        this.form.clients_id = obj
      },
     },
    created() {
      this.setHeader('Контакты')
      this.page = this.pagination.page
      this.findContactsType()
      this.getClientsId()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }


</style>

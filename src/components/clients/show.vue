<template>
 <section>
   <div v-for="client in clients" :key="client.id">
    <div style="text-align: left">
     <div class="row" >
          <div class="col-1"></div>
          <div class="col-9 " style="background-color: #bdccd7">
             <p>{{client.name}}</p>
          </div>
          <div class="col-1">
            <button type="button" class="close" @click="returnTo()" aria-hidden="true">×</button>
          </div>
       </div>  
      
      <div class="row" >
          <div class="col-1"></div>
            <div class="col-3" style="" >
                    <b>Полное название: </b>{{ client.full_name }}<br>  
                    <b>Тип клиента: </b>{{ client.clienttype_id }}<br> 				         
                    <br><b>Сфера деятельности: </b>{{ client.sphere_id }}<br> 	  
                    <b>Менеджер: </b>{{ getManager(client.manager).username }}<br> 
                    <b>Область: </b>{{ getCity(client.city_id).name }}<br> 
                    <b>ИНН: </b>{{ client.inn }}<br>     
            </div>
            <div class="col-3 ">
                    <b>Сайт: </b><a href=""  target="_blank">{{}}</a><br>
                    <b>ОГРН: </b>{{ client.ogrn }}<br> 
                    <b>КПП: </b>{{ client.kpp }}<br> 
                    <b>ОКПО: </b>{{ client.sphere_id }}<br> 
                    <b>Юр. адрес: </b>{{ client.sphere_id }}<br> 
                    <b>Фактический адрес: </b>{{ client.factaddress }}<br> 
            </div>
            <div class="col-3 ">
                <b>Почтовый адрес: </b>{{ client.postaddress }}<br> 
                <b>Банк: </b>{{ client.bank }}<br> 
                <b>БИК: </b>{{ client.bik }}<br> 
                <b>Р/С: </b>{{ client.rs }}<br> 
                <b>К/С: </b>{{ client.sphere_id }}<br>      
            </div>
              <div class="col-1"></div>
        </div>
        </div> 
        <div class="row" style="margin-top: 30px">
            <div class="col-1"></div>
            <div class="col-10" style="text-align: left;padding: 0;" >
              <panel :id ="id" ></panel>
            </div>
            <div class="col-1"></div>
        </div>     
    </div>
 </section>
</template>
<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'    
//  import pagination from "../../utils/pagination"
  import Panel from './showComponent/panel'



  export default {
    mixins: [Acl],
    name: 'show',
    data () {
      return {
        form:{},
        search: null,
        page: null,
        edit: null,
        user: null,
        id: this.$route.params.id
      }
    },
    components: {
//      pagination,
      Panel
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          clients: 'clients/datalist',
          pagination: 'clients/pagination',
          users: 'app/users',
          citys: 'city/datalist'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
          setMessage: 'app/setMessage',
          setError: 'app/setError',
          setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          save: 'outcall/save',
          find: 'clients/findOnId',
          drop: 'outcall/delete',
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
        this.find(this.$route.params.id)  
      },
      returnTo(){
        this.$router.push('../../mybuisness/')
      },
       getManager(id){
          var res = {}
          this.users.map((el) => {
            if(id == el.id){
                res = el
            }
          })
          return res
      },
       getCity(id){
          var res = {}
          this.citys.map((el) => {
            if(id == el.id){
                res = el
            }
          })
          return res
      }
    
    },
    created() {
      this.findData()
      this.setHeader('Компания')
    }
  }
</script>
<style >
.panel-default {
    border-color: #ddd;
}
.panel-heading {
    color: #333;
    background-color: #f5f5f5;
    border-color: #ddd;
    text-align:left
}
.panel {
  border: 1px solid ;
  text-align:left
   
}
.panel-body {
    padding: 15px;
}
</style>
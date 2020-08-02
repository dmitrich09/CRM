<template>
 <section >
    <div  v-for="appl in appls" :key="appl.id"> 
    <div style="text-align: left">
        <div class="row" >
            <div class="col-1"></div>
            <div class="col-9 " style="background-color: #bdccd7">
                <p>{{appl.name}}</p>
            </div>
            <div class="col-1">
                <button type="button" class="close" @click="returnTo()" aria-hidden="true">×</button>
            </div>
        </div>  
        <div class="row" >
            <div class="col-1"></div>
                <div class="col-3" style="" >
                        <b>Полное название: </b>{{ }}<br>  
                        <b>Тип клиента: </b>{{ appl.manager_id }}<br> 				         
                        <br><b>Рассчитывает </b>{{ getManager(appl.countmanager_id).username }}<br> 	  
                        <b>Документ нужен на руки: </b>{{ appl.doc_on_hand}}<br> 
                        <b>ЛПР: </b>{{ appl.is_lpr}}<br>
                </div>
                <div class="col-3 ">
                        <b>Др. предложения: </b><a href=""  target="_blank">{{ appl.another_offer}}</a><br>
                        <b>Для чего документ: </b>{{ appl.doc_for_what }}<br> 
                        <b>ОКП: </b>{{ appl.okp }}<br> 
                        <b>ОКПД2: </b>{{ appl.okpd2 }}<br> 
                </div>
                <div class="col-3 ">
                    <b>ТНВЭД: </b>{{ appl.tnved }}<br> 
                    <b>По какому документу выпуск: </b>{{ appls.documentprod }}<br> 
                    <b>Область применения: </b>{{ appl.bik }}<br> 
                    <b>Страна изготовитель: </b>{{ appl.countrymade }}<br> 
                    <b>Страна заявитель: </b>{{ appl.countryask }}<br>      
                    <b>Выезд: </b>{{ appl.Comment }}<br>      
                    <b>Реальные испытания: </b>{{ appl.test }}<br>      
                </div>
                <div class="col-1"></div>
            </div>
       </div> 
       <div class="row" style="margin-top: 30px">
          <div class="col-1"></div>
             <div class="col-10">
                <div class="panel row">
                    <div class="col" >
                        <a href="#" @click="commentAppl = true;item=false;lead=false;application=false;"  >Комментарии</a>
                    </div>
                    <div class="col">
                        <a href="#" @click="item = true;commentAppl = false;lead=false;application=false;"  >Расчеты </a>
                    </div>
                    <div class="col">
                        <a href="#" @click="lead=true;outcall=false;task=false;application=false;kp=false;"  >Файлы</a>
                    </div>
                    <div class="col">
                        <a href="#" @click="lead=true;outcall=false;task=false;application=false;kp=false;"  >Почта</a>
                    </div>
                </div>
          </div>     
      </div> 
      <div class="row"  style="margin-top: 30px; ">
          <div class="col-1"></div>
          <div class="col-10" >
              <commentAppl  v-if="commentAppl" :object_id="object_id" ></commentAppl>
              <item v-if="item" :application_id ="id" ></item>
          </div>
          <div  class="col-1"></div>
      </div> 

    </div>
 </section>
</template>
<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import commentAppl from './components/commentAppl'
  import Item from '../item/index'


export default {
    mixins: [Acl],
    name: 'showAppls',
    data () {
      return {
        commentAppl: false,
        item: false,
        form:{},
        search: null,
        page: null,
        edit: null,
        user: null,
        object_id: null,
        id: this.$route.params.id
      }
    },
    components: {
      commentAppl:commentAppl,
      Item,
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          appls: 'application/datalist',
          users: 'app/users'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
          setMessage: 'app/setMessage',
          setError: 'app/setError',
          setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
           find: 'application/findOnId',
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
      getObjectId(){
         this.object_id = this.id
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
      }
    
    },
    created() {
      this.findData()
      this.setHeader('Заявка')
      this.getObjectId()
    }
  }
</script>
<style >

</style>
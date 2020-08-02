<template>
  <section>
    <div class="panel row" v-bind:style="height">
        <div class="col" >
            <a href="#"  @click="contacts=true;outcall = false;lead=false;application=false;kp=false;agreement=false;ork=false;story=false;callStory=false;doc=false;comments=false" :class="[contacts ? 'color ' : ''] ">Контакты <span class="badge">{{  contactsPagination.total ? contactsPagination.total : 0 }}</span></a>
        </div>
         <div class="col">
            <a href="#"  @click="outcall = true;contacts=false;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false;story=false;callStory=false;doc=false" :class="[outcall ? 'color ' : '']" >Звонки  <span class="badge">{{  outcallPagination.total ? outcallPagination.total : 0 }}</span></a>
        </div>
         <div class="col">
             <a href="#"  @click="lead=true;contacts=false;outcall = false;application=false;kp=false;agreement=false;ork=false;comments=false;story=false;callStory=false;doc=false" :class="[lead ? 'color ' : '']">ЭК<span class="badge"  >{{  leadPagination.total ? leadPagination.total : 0 }}</span></a>
         </div>
         <div class="col">
             <a href="#"  @click="application=true;contacts=false;outcall = false;lead=false;kp=false;agreement=false;ork=false;comments=false;story=false;callStory=false;doc=false" :class="[application ? 'color ' : '']" >Заявки<span class="badge" >{{  applPagination.total ? applPagination.total : 0}}</span></a>
        </div>
        <div class="col">
            <a href="#"    @click="contacts=false;outcall = false;lead=false;application=false;kp=true;agreement=false;ork=false;comments=false;story=false;callStory=false;doc=false" :class="[kp ? 'color ' : '']" >Коммерческие<span class="badge" >{{  kpPagination.total ? kpPagination.total : 0}}</span></a>
        </div>
        <div class="col">
            <a href="#"   @click="contacts=false;outcall = false;lead=false;application=false;kp=false;agreement=true;ork=false;comments=false;story=false;callStory=false;doc=false" :class="[agreement ? 'color ' : '']">Договоры<span class="badge" >{{  agreementPagination.total ? agreementPagination.total : 0}}</span></a>
        </div>
        <div class="col">
            <a href="#"   @click="contacts=false;outcall = false;lead=false;application=false;kp=false;agreement=false;ork=true;comments=false;story=false;callStory=false;doc=false" :class="[ork ? 'color ' : '']"  >ОРК<span class="badge" >{{  orkPagination.total ? orkPagination.total : 0}}</span></a>
        </div>
         <div class="col">
            <a href="#"   @click="callStory=true;contacts=false;outcall = false;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false;doc=false;story=false;" :class="[callStory ? 'color ' : '']"  >История звонков<span class="badge" >{{  callsPagination.total ? callsPagination.total : 0}}</span></a>
        </div>
         <div class="col">
            <a href="#"   @click="story=true;callStory=false;contacts=false;outcall = false;;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false;doc=false" :class="[story ? 'color ' : '']"  >История<span class="badge" ></span></a>
        </div>
         <!-- <div class="col">
            <a href="#"   @click="doc=true;story=false;callStory=false;contacts=false;outcall = false;;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false;" :class="[doc ? 'color ' : '']"  >Док-ты<span class="badge" >{{  documentPagination.total ? documentPagination.total : 0}}</span></a>
        </div> -->
          <div class="col">
            <a href="#"   @click="isActive = 'doc';showComponent('doc') " :class="[doc ? 'color ' : '']"  >Док-ты<span class="badge" >{{  documentPagination.total ? documentPagination.total : 0}}</span></a>
        </div>
        <div class="col ">
            <button class="btn btn-primary"  @click="showForm = !showForm;changeHeight()" >НАЙТИ</button>
        </div>
        <div class="row"  v-if="showForm" style="padding-left: 11%;margin-top: 40px"> 
            <div class="col">
                <input type="text" class="form-control"  v-model="tempForm.query" placeholder="Введите запрос">
            </div>
            <div class="col">
                <input type="text" class="form-control"  v-model="tempForm.active" placeholder="Активна">  
            </div>
            <div class="col">
               <date-picker v-model="tempForm.dateFrom"  valueType="format"  ></date-picker>
            </div>
                <div class="col">
                 <date-picker v-model="tempForm.dateTo"  valueType="format" ></date-picker>
            </div>
           <div class="col" >
                 <select  class="select"  v-model="tempForm.userId" >
                    <option value=""> Выберите менеджера</option>
                    <option  v-for="manager in managers" :key="manager.id" :value="manager.id" >{{manager.username}}</option>
                </select>  
            </div>
            <div class="col">
                <button class="btn btn-primary" @click="sendForm()"  style="width:120px; margin-right: 10px;border-radius: 20px"  > Получить</button>
            </div>
        </div>
    </div>
    <div class="row"  style="margin-top: 20px; ">
        <div class="col-1"></div>
        <div class="col-10">
            <contacts  v-if="contacts" :clients_id="id"></contacts>
            <outcall  v-if="outcall" :showButtonForm="true"  :clients_id="id"></outcall>
            <lead  v-if="lead" :clients_id="id" :showButtonForm="true"></lead>
            <application  v-if="application"  ></application>
            <kp  v-if="kp" ></kp>
            <agreement  v-if="agreement" ></agreement>
            <ork  v-if="ork" ></ork>
            <callStory  v-if="callStory" ></callStory>
            <story  v-if="story" ></story>
            <doc  v-if="doc" ></doc>
            <comments  v-if="comments" ></comments>
        </div>
        <div class="col-1"></div>
    </div>
    </section>
</template>

<script>
import Contacts from '../showComponent/contacts'
import Outcall from '../../mybuisness/outcall'
import Lead from '../../mybuisness/lead'
import Application from '../../mybuisness/application'
import Kp from '../..//mybuisness/kp'
import Agreement from '../../mybuisness/agreement'
import Ork from '../../mybuisness/ork'
import callStory from '../showComponent/callStory'
import Story from '../showComponent/story'
import Doc from '../showComponent/doc'
import Comments from '../../comments'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import 'vue2-datepicker/locale/ru'
import Vuex from 'vuex'

export default {
    name: 'panel',
    props: ['id'],
    data () {
        return {
            tempForm: { clients_id: this.id}, 
            showForm: false,    
            height: '',
            comments:false,
            contacts: false,
            outcall: false,
            lead: false,
            application: false,   
            kp: false,
            agreement: false,
            ork: false,
            callStory:false,
            story:false,
            doc:false,
            isActive: null
        }
    },
     components:{
        Contacts,
        DatePicker ,
        Outcall,
        Lead,
        Application,
        Kp,
        Agreement,
        Ork,
        callStory,
        Story,            
        Doc,  
        Comments          
    },
    watch:{
        valComent: function(){
            this.comments = true
            this.contacts = false
            this.outcall= false
            this.lead= false
            this.application= false
            this.kp = false,
            this.agreement = false
            this.ork = false
            this.callStory = false
            this.story =false
            this.doc = false   
        }
    },
    computed:{
        ...Vuex.mapGetters({
                user: 'auth/user',
                apiUrl: 'app/apiUrl',
                storeForm: 'mybuisness/form',   
                managers: 'app/users',
                valComentStore: 'comments/valComent',
                contactsPagination: 'contacts/pagination',
                outcallPagination: 'outcall/pagination',
                leadPagination: 'lead/pagination',
                applPagination: 'application/pagination',
                kpPagination: 'kp/pagination',
                agreementPagination: 'agreement/pagination',
                orkPagination: 'ork/pagination',
                callsPagination: 'calls/pagination',
                documentPagination: 'document/pagination',
        }),
        form(){
            return  Object.assign({}, this.storeForm)  
        },
        valComent(){
            return  Object.assign({}, this.valComentStore)  
        },
           
      },
    methods:{
        ...Vuex.mapMutations({
            setForm:'mybuisness/setForm',
            setHeader:'app/setCurrentPageHeader' 
         }),
        ...Vuex.mapActions({
            findOutcall: 'outcall/find',
            findLead: 'lead/find',
            findApplication: 'application/find',   
            findKp: 'kp/find',
            findAgreement: 'agreement/find',
            findOrk: 'ork/find',
            findCalls: 'calls/find',
            findContacts: 'contacts/find',
            findDocument: 'document/find',
            findUsers: 'app/getUsers'
        }),
        commentShow(){
            if(this.valComent.id != ''){
                this.comments = true
            }
        },
        sendForm(){
            this.setForm(this.tempForm)
                this.findOutcall(this.storeForm)
                this.findLead(this.storeForm)
                this.findApplication(this.storeForm)  
                this.findKp(this.storeForm)
                this.findAgreement(this.storeForm)
                this.findOrk(this.storeForm)
                this.findCalls(this.storeForm)
                this.findContacts(this.storeForm)
                this.findDocument(this.storeForm)
        },
        changeHeight(){
            if(this.showForm != false){
                return this.height = 'height: 200px'
            }
             if(this.showForm == false){
                return this.height = 'height: 70px'
            }
        },
        showComponent(){
 //       if(this.isActive == val){
 //         ('this.'+ val) = true
 //          console.log(`this. ${val}` = true)
 //          console.log('this.'+ String(val))
 //   }
    }
    },
    created (){
        this.setHeader('Аккаунт компании') 
        this.sendForm()
    }
  }
</script>
<style >
.panel{
    margin-top: 2%;
    margin-right: 1%;
    margin-left: 1%;
    border: 1px solid black;
    font-size: 15px;
    height: 150px;
    align-content: center;
}
.panel.a{
    color:blue
}
.badge {
    display: inline-block;
    min-width: 10px;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: #777;
    border-radius: 10px;
}
.color{
    color: red
}


</style>
<template>
  <section >
    <div class="pane row" :style="height" style="font-size: 12px">
        <div class="col" >
            <a href="#"  @click="task=true;outcall=false;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false"  :class="[task ? 'color ' : ''] ">Задачи <span class="badge">{{ taskPagination.total ? taskPagination.total : 0 }}</span></a>
        </div>
         <div class="col">
            <a href="#" @click="outcall = true;task = false;lead=false;application=false;kp=false;agreement=false;ork=false;comments=false" :class="[outcall ? 'color ' : '']" >Звонки  <span class="badge">{{  outcallPagination.total ? outcallPagination.total : 0 }}</span></a>
        </div>
         <div class="col">
             <a href="#" @click="lead=true;outcall=false;task=false;application=false;kp=false;agreement=false;ork=false;comments=false"  :class="[lead ? 'color ' : '']">ЭК<span class="badge"  >{{  leadPagination.total ? leadPagination.total : 0 }}</span></a>
         </div>
         <div class="col">
             <a href="#" @click="application=true;lead=false;outcall=false;task=false;kp=false;agreement=false;ork=false;comments=false" :class="[application ? 'color ' : '']" >Заявки<span class="badge" >{{  applPagination.total ? applPagination.total : 0  }}</span></a>
        </div>
        <div class="col">
            <a href="#" @click="kp=true;application=false;lead=false;outcall=false;task=false;agreement=false;ork=false;comments=false"  :class="[kp ? 'color ' : '']" >Коммерческие<span class="badge" >{{   kpPagination.total ? kpPagination.total : 0 }}</span></a>
        </div>
        <div class="col">
            <a href="#" @click="agreement=true;kp=false;application=false;lead=false;outcall=false;task=false;ork=false;comments=false " :class="[agreement ? 'color ' : '']">Договоры<span class="badge" >{{  agreementPagination.total ? agreementPagination.total : 0}}</span></a>
        </div>
        <div class="col">
            <a href="#" @click="ork=true;agreement=false;kp=false;application=false;lead=false;outcall=false;task=false;comments=false"  :class="[ork ? 'color ' : '']"  >ОРК<span class="badge" >{{  orkPagination.total ? orkPagination.total : 0}}</span></a>
        </div>
        <div class="col ">
            <button class="btn btn-primary"  @click="showForm = !showForm;changeHeight()" >НАЙТИ</button>
        </div>
        <div class="row"  v-if="showForm" style="padding-left: 11%;margin-top: 40px">
            <div class="col">
                <div style=" font-size: 70%;">Введите запрос</div> 
                <input type="text" class="form-control"  v-model="tempForm.query" placeholder="">
            </div>
            <div class="col">
                <div style=" font-size: 70%;">Активность</div> 
                <select  class="select"  v-model="tempForm.isactive" >
                    <option value="">Активность</option>
                    <option value="1">Активна</option>
                    <option value="2">Согласие</option>
                    <option value="3">Отказ</option>
                </select>
            </div>
            <div class="col">
                <div style=" font-size: 70%;">Дата от</div> 
               <date-picker v-model="tempForm.dateFrom"  valueType="format"  ></date-picker>
            </div>
                <div class="col">
                    <div style=" font-size: 70%;">Дата до</div> 
                    <date-picker v-model="tempForm.dateTo"  valueType="format" ></date-picker>
                </div>
           <div class="col" >
               <div style=" font-size: 70%;">Выберите менеджера</div> 
                 <select  class="select"  v-model="tempForm.userId" >
                    <option  v-for="manager in managers" :key="manager.id" :value="manager.id" >{{manager.username}}</option>
                </select>
            </div>
            <div class="col">
                <button class="btn btn-primary" @click="sendForm()"  style="width:120px; margin-right: 10px;border-radius: 20px"  > Получить</button>
            </div>
        </div>
    </div>

    <div class="row"  style="margin-top: 30px; ">
        <div style="width: 20px"></div>
        <div class="col" >
            <task  v-if="task" ></task>
            <outcall  v-if="outcall" ></outcall>
            <lead  v-if="lead" ></lead>
            <application  v-if="application" ></application>
            <kp  v-if="kp" ></kp>
            <agreement  v-if="agreement" ></agreement>
            <ork  v-if="ork" ></ork>
            <createAppls  v-if="createAppls" ></createAppls>
            <createLead  v-if="createLead" ></createLead>
            <div v-if="comments" class="row">
               <div class="col-2"> </div>
                    <div  class="col-8">
                        <comments   ></comments>
                    </div>
               <div class="col-2"> </div>
            </div>
        </div>
         <div style="width: 20px"></div>
    </div>
    </section>
</template>

<script>
import Task from './task'
import Outcall from './outcall'
import Lead from './lead'
import Application from './application'
import Kp from './kp'
import Agreement from './agreement'
import Ork from './ork'
import CreateAppls from '../create/createAppls'
import CreateLead from '../create/createLead'
import Comments from '../comments'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import 'vue2-datepicker/locale/ru'
import Vuex from 'vuex'

export default {
    name: '',
    data () {
        return {
            tempForm: { userId: ''},
            showForm: false,
            task: false,
            outcall: false,
            lead: false,
            application: false,
            kp: false,
            agreement: false,
            ork: false,
            comments: false,
            createAppls: false,
            createLead: false,
            commentId: null,
            userId: null,
            height:''
        }
    },
     components:{
        Task,
        Outcall,
        Lead,
        Application,
        Kp,
        Agreement,
        Ork,
        Comments,
        CreateAppls,
        CreateLead,
        DatePicker
    },
    watch:{
        valComent: function(val){
            val = true
            this.task = false,
            this.outcall = false,
            this.lead = false,
            this.application = false,
            this.kp = false,
            this.agreement = false,
            this.ork = false,
            this.createAppls =  false,
            this.createLead = false,
            this.comments = val
        }
    },
    computed:{
        ...Vuex.mapGetters({
                user: 'auth/user',
                apiUrl: 'app/apiUrl',
                taskPagination: 'task/pagination',
                outcallPagination: 'outcall/pagination',
                leadPagination: 'lead/pagination',
                applPagination: 'application/pagination',
                kpPagination: 'kp/pagination',
                agreementPagination: 'agreement/pagination',
                orkPagination: 'ork/pagination',
                storeForm: 'mybuisness/form',
                managers: 'app/users',
                valComentStore: 'comments/valComent'
        }),
        form(){
            return  Object.assign({}, this.storeForm)
        },
        valComent(){
            return  Object.assign({}, this.valComentStore)
        }
      },
    methods:{
        ...Vuex.mapMutations({
            setForm:'mybuisness/setForm',
            setHeader:'app/setCurrentPageHeader',
         }),
        ...Vuex.mapActions({
            findTask: 'task/find',
            findOutcall: 'outcall/find',
            findLead: 'lead/find',
            findApplication: 'application/find',
            findKp: 'kp/find',
            findAgreement: 'agreement/find',
            findOrk: 'ork/find',
            findUsers: 'app/getUsers',
            findCity: 'city/findAll'
        }),
        sendForm(){
            this.setForm(this.tempForm)
                this.findTask(this.storeForm)
                this.findOutcall(this.storeForm)
                this.findLead(this.storeForm)
                this.findApplication(this.storeForm)
                this.findKp(this.storeForm)
                this.findAgreement(this.storeForm)
                this.findOrk(this.storeForm)
        },
        changeHeight(){
            if(this.showForm != false){
                return this.height = 'height: 200px;font-size:12px'
            }
            if(this.showForm == false){
                return this.height = 'height: 70px;font-size:12px'
            }
        },
        getUserId(){
            var obj =  Object.assign({}, this.user)
            this.tempForm.userId = obj.id
        },
    },
    created (){
        this.setHeader('Мои дела')
        this.findUsers()
        this.getUserId()
        this.sendForm()
        this[this.$route.query.active] = true
    }
  }
</script>
<style scoped>
.pane{
    margin-right: 1%;
    margin-left: 1%;
    border: 1px solid black;
    font-size: 16px;
    height: 70px;
    align-content: center;
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

  






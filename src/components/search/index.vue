<template>
  <section>
    <div class="panel row" style="font-size: 12px" >
        <div class="col" >
            <a href="#"  @click="showComponent('name')" :class="[name ? 'color' : ''] ">Название <span class="badge">{{ byName.count ? byName.count : 0  }}</span></a>
        </div>
        <div class="col" >
            <a href="#"   @click="showComponent('phone')"  :class="[phone ? 'color' : ''] ">Телефон <span class="badge">{{ byPhone.count ? byPhone.count : 0  }}</span></a>
        </div>
        <div class="col" >
            <a href="#"   @click="showComponent('inn')"  :class="[inn ? 'color ' : ''] ">ИНН<span class="badge">{{ byInn.count ? byInn.count : 0  }}</span></a>  
        </div>
        <div class="col" >
            <a href="#" @click="showComponent('AddressShow')"   :class="[AddressShow ? 'color ' : ''] ">Адрес <span class="badge">{{ byAddress.count ? byAddress.count : 0  }}</span></a>
        </div>
         <div class="col" >
            <a href="#"  @click="showComponent('email')" :class="[email ? 'color ' : ''] ">E-mail <span class="badge">{{ byEmail.count ? byEmail.count : 0  }}</span> </a>
        </div>
        <div class="col" >
            <a href="#" @click="showComponent('staff')"   :class="[staff ? 'color ' : ''] ">Сотрудники <span class="badge">{{ byContName.count ? byContName.count : 0  }}</span> </a>
        </div>
    </div>
    <div class="row"  style="margin-top: 20px ">
        <div class="col-1"></div>
        <div class="col-10">
          <name v-if="name" :search="search" ></name>
          <phone v-if="phone" :search="search" ></phone> 
          <inn v-if="inn" :search="search" ></inn>
          <email v-if="email" :search="search" ></email>  
          <staff v-if="staff" :search="search" ></staff>
          <AddressShow v-if="AddressShow" :search="search"></AddressShow> 
        </div>
        <div class="col-1"></div>
    </div>
    </section>
</template>

<script>

import Vuex from 'vuex'
import Name from '../search/showComponent/name'
import Phone from '../search/showComponent/phone'
import Inn from '../search/showComponent/inn'
import Email from '../search/showComponent/email'
import Staff from '../search/showComponent/staff'
import AddressShow from '../search/showComponent/addres'

export default {
    name: '',
    data () {
        return {   
            name: false,
            phone: false,  
            inn: false,
            email: false,
            AddressShow: false,
            staff: false,
            page: null,
        }
    },
    components:{
        AddressShow,
        Name ,
        Phone,
        Inn,
        Email ,
        Staff 
    },
    computed:{
        ...Vuex.mapGetters({
            user: 'auth/user',
            apiUrl: 'app/apiUrl',
            search: 'app/search',
            byName: 'search/byName',
            byPhone: 'search/byPhone',
            byAddress: 'search/byAddress',   
            byInn: 'search/byAddress',
            byEmail: 'search/byEmail',
            byContName: 'search/byContName',
            pagination: 'search/pagination',
        }),  
    },
    methods:{
        ...Vuex.mapMutations({
            setForm:'mybuisness/setForm',
            setHeader:'app/setCurrentPageHeader'   
         }),
        ...Vuex.mapActions({
            findClients: 'search/findCients',
            findClientsContact: 'search/findClientsContact',
            findUsers: 'app/getUsers',
        }),
      
        sendForm(){
            this.findClients({search:this.search})
            this.findClientsContact({search:this.search})
        },
        showComponent(comp){ 
            this.name = false
            this.phone = false 
            this.inn = false  
            this.AddressShow = false   
            this.email = false  
            this.staff = false  
            if(comp != null && comp != '') {
                this[comp] = true
            }
        },
        changeColor(comp){
           this[comp] 
        }
    },
    created(){
        this.setHeader('Поиск') 
        this.sendForm()
        this.findUsers()
        
    }
  }
</script>
<style scoped>
.panel{
    margin-top: 2%;
    margin-right: 1%;
    margin-left: 1%;
    border: 1px solid black;
    font-size: 15px;
    height: 80px;
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
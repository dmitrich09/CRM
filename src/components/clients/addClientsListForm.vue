<template>
    <section>
        <p>Компании</p>
        <div class="form-row"   >
           <div class="col-2">
                  <select class="select"  v-model="form.user" >
                     <option value="" >Все менеджеры</option>
                     <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
            </div>
            <div class="col-2">
                  <select class="select"  v-model="form.city" >
                     <option value="" >Город</option>
                     <option  v-for="city in citys" :key="city.id" :value="city.id" >{{city.name}}</option>
                  </select>
            </div>
            <div class="col-2">
                <select class="select"  v-model="form.type" >  
                    <option value="" >Тип</option>
                    <option  v-for="(clientType, id) in clientTypes" :key="id"  v-bind:value="clientType.id" >{{clientType.name}}</option>
                </select>
            </div> 
             <div class="col-2">
                <select class="select"  v-model="form.abc" >
                     <option value="" >Категории</option>
                    <option  v-for="(ABCList, id) in ABCLists" :key="id"  v-bind:value="ABCList.id" >{{ABCList.name}}</option>
                </select>
            </div>    
            <div class="col-2"  style="text-align: left;"></div>
            <div class="col-2">
                 <a class="btn btn-success upload" style="border-radius: 6px;"  :href="apiUrl + 'clients/downloadlist?type='+form.type +'&city='+ form.city +'&abc='+form.abc +'&user='+form.user"  target="_blank" >CКАЧАТЬ Excel</a>
            </div>
        </div>

     
        <div class="form-row"  style="margin-top: 30px" >
            <div class="col-2">
                  <select class="select"  v-model="form.cityUp" >
                       <option value="" >Город</option>
                     <option  v-for="city in citys" :key="city.id" :value="city.id" >{{city.name}}{{city.id}}</option>
                  </select>
            </div>
            <div class="col-2">
                <select class="select"  v-model="form.typeUp" >
                     <option value="" >Тип</option>
                    <option  v-for="(clientType, id) in clientTypes" :key="id"  v-bind:value="clientType.id" >{{clientType.name}}</option>
                </select>
            </div> 
              <div class="col-2">
                  <select class="select"  v-model="form.userUp" >
                     <option value="" >Все менеджеры</option>
                     <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
            </div> 
             <div class="col-2"></div>
             <div class="col-2"></div>
             <div class="col-2" >
                <label for="file-upload" class="file-upload">
                    <i class="fa fa-cloud-upload"></i> Загрузите файл
                </label>
                <input id="file-upload" type="file" name="f" class="form-control "  ref="file"  @change="uploadFile()"> 
            </div>
        </div>
    </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
    import Http from '../../utils/http'
     
 export default {
    mixins: [Acl],
    name: 'citypek',
    props:['showButtonForm'],
    data () {
      return {
        showForm: false,
        file:'',
        form:{
          userUp:'',
          typeUp:'',
          cityUp:'',
          user:'',
          city: '',
          type: '',
          abc: ''
        },
      }
    },
    components: {
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        citys: 'city/datalist',
        clientTypes: 'dictionary/clientType' ,
        ABCLists: 'dictionary/ABCList',
        users: 'app/users',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
    ...Vuex.mapActions({
        findCity: 'city/findAll',
        findUsers: 'app/getUsers',
    }),
    uploadFile() {
     
      this.file = this.$refs.file.files[0];
      
      var formData = new FormData()
      formData.append('file', this.file)
      formData.append('city', this.form.cityUp)   
      formData.append('type', this.form.typeUp)
      formData.append('user', this.form.userUp)
  
      Http.post(this.apiUrl + 'clients/uploadlist',    
        formData,
            {
                headers: {
                  'Content-Type': 'multipart/form-data',
                }
            }
        )
        .then(() => {
            this.$store.commit('app/setError','Sucsses upload!',{root: true})
        })
        .catch(err => {
            if (err.response) {
              this.$store.commit('app/setError', err.response.data.message, {root: true})  
      
            }
        })
        this.$refs.file.value = null  
      },
      
   
    },
    created() {
      this.setHeader('Скачать и Загрузить Xls')
      this.findCity()
      this.findUsers()
     
    }
  }
</script>
<style >
input[type="file"] {
    display: none;
}
.file-upload {
    border-radius: 6px;
    color: #ebf1f5;
    border: 1px solid #109CF1;
    display: inline-block;
    width: 130px;
    font-size: 14px;
    height: 42px;
    line-height: 37px;
    background-repeat: no-repeat;
    text-align: center;
    cursor: pointer;
    background-position: 10px center;
    background-color: #4695c3;
    border-radius: 4px;
}
.upload{
  margin-top: 20px;
  border-radius: 6px;
}
</style>
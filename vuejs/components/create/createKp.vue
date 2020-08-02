 <template>
  <section>
      <div class="row " >
          <div class="col-2"></div>
              <div  class="col-8">
                     <h3>Создать Коммерческое</h3>
                      <div style=" font-size: 70%;margin-right: 90%;">Дата контакта</div>
                          <div class="row" >
                              <date-picker v-model="form.startdate"  valueType="format" ></date-picker>
                          </div>
                          <div class="row">
                                <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.client_name" placeholder="И.О. Клиента">  
                          </div>
                          <div class="row" style="margin-top: 50px">
                              <button class="btn btn-primary" style="width:120px;border-radius: 20px" @click="saveData()"> Дoбавить</button>
                          </div>
                      <div>
              </div>
          </div>
          <div class="col-2"></div>    
      </div>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'

export default {
    mixins: [Acl],
    name: 'createKp',
    data () {
      return {
        form:{
          startdate: '',
          client_name:'',
          state: 10,
          source_id: 10,
          application_id: this.$route.params.id
        },
        search: null,
        page: null,
        edit: null,
        id:   this.$route.params.id
      }
    },
    components: {
      DatePicker
   
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl'
      }),
      dateComp() {
         return  new Date().getDate()+'.'+ (new Date().getMonth()+1)+'.'+ new Date().getFullYear()+' '+new Date().getHours()+':'+ this.compMinutess
      },
      compMinutess(){
            var val =[]
            var str = String(new Date().getMinutes()) 
            var arr = str.split('')

            if(arr.length == 2){
              return str
            }
            if(arr.length < 2){
              return '0' + arr[0]
            }
            return val
      },
      
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'kp/save',
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
         .then(() => {
            this.$router.push('/mybuisness')
        })
      }
    },
    created() {
      this.setHeader('Cоздать Коммерческое')
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
  select {
    display: block   ;
    width: 100% !important;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.mx-input-wrapper {
    width: 100% !important;
}
</style>

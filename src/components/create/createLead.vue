 <template>
  <section>
      <div class="row " >
          <div class="col-2"></div>
              <div  class="col-8">
                   <button type="button" class="close" @click="returnTo()" aria-hidden="true" title="ПЕРЕЙТИ В ЭК">×</button>
                     <h3>Создать ЭК</h3>
                      <div style=" font-size: 70%;margin-right: 90%;">Дата контакта</div>
                          <div class="row">
                              <date-picker v-model="form.contactdate"  valueType="format" ></date-picker>
                          </div>
                          <div class="row" style="margin-top:40px">
                             <div style=" font-size: 70%;">Источник</div> 
                                <select class="select"  v-model="form.source_id" >
                                  <option  v-for="source in sources" :key="source.id" :value="source.id" >{{source.name}}</option>
                                </select>
                          </div>
                          <div class="row">
                                <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.interesttype" placeholder="Тип интереса">  
                          </div>
                          <div class="row">
                                <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.comment" placeholder="Комментарий">  
                          </div>
                          <div class="row" style="margin-top: 50px">
                              <button class="btn btn-primary" style="width:120px;border-radius: 20px" @click="saveData()"> Дoбавить</button>
                          </div>
                      <div>
              </div>
          </div>
          <div class="col-2"></div>   
            <img scr="/static/images/icons/art.svg" alt="">
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
    name: 'createLead',
    data () {
      return {
        form:{
          contactdate:'',
          source_id:'',
          interesttype: '',
          comment: '',
        },
        search: null,
        page: null,
        edit: null,
        outcall_id: '',
        clients_id: ''
      }
    },
    components: {
      DatePicker,
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          sources: 'source/datalist',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'lead/save',
        findSource: 'source/find',
      }),
      saveData(){
        this.setError(null)
        this.save(this.form).then(() => {
          this.$router.push('/mybuisness')
        })
     
//        this.setError('Успешно добавлено!')
      },
      returnTo(){
        this.$router.push('../../mybuisness/lead')
      },
      outcallPar(){
        var obj =  this.$route.params.id
        var arr = obj.split('&&')
        this.form.outcall_id = arr[0]
      },
      clientsPar(){
        var obj =  this.$route.params.id
        var arr = obj.split('=')
        this.form.clients_id = arr[1]
      },
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
    created() {
      this.setHeader('Cоздать ЭК')
      this.findSource(),
      this.outcallPar(),
      this.clientsPar(),
      this.dateComp()
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
</style>

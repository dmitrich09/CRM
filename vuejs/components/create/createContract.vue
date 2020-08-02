 <template>
  <section>
      <div class="row " >
          <div class="col-2"></div>
              <div  class="col-8">
                     <h3>Оформить Контракт</h3>
                        
                            <div class="row" >
                                  <div style=" font-size: 70%;margin-right: 90%;">Дата контакта</div>
                                  <date-picker v-model="form.startdate"  valueType="format" ></date-picker>
                            </div>
                            <div  class="row" style="margin-top: 30px" > 
                                <select class="select"  v-model="form.tax" >
                                    <option value="20">ИП Филимонов</option>
                                    <option value="10"> ООО Современные решения</option>
                                </select>
                            </div>
                            <div class="row">
                                  <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.person" placeholder="в лице">  
                            </div>
                            <div class="row">
                                  <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.short_person" placeholder="подпись">  
                            </div>
                            <div class="row">
                                  <input type="text" class="form-control"  style="margin-top: 30px"  v-model="form.basement" placeholder="на основании">  
                            </div>
                             <div class="row"  style="margin-top: 30px"> 
                                <textarea class="form-control"  v-model="form.options" placeholder="Для чего документ" style="height: 100px;"  ></textarea>
                            </div>
                            <!-- <div class="row"  style="margin-top: 30px"> 
                                <CKEditor v-model="editorData" :config="editorConfig"></CKEditor>
                            </div>  -->
                             
                            <div class="row" style="margin-top: 50px">
                                <button class="btn btn-primary" style="width:120px;border-radius: 20px" @click="saveData()"> Дoбавить</button>
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
  // import Vue from 'vue';
  // import CKEditor from 'ckeditor4-vue';

  // Vue.use( CKEditor );
 

  export default {
    mixins: [Acl],
    name: 'createKp',
    data () {
      return {
        form:{
            person: '',
            short_person:'',
            basement: '',
            kp_id: this.$route.params.id,
            options: "СТОРОНЫ установили срок вышеуказанной услуги с момента оплаты 100 % суммы договора и предоставления полного пакета документов,    Срок вышеуказанной работы:      -Разработка технических условий (ТУ) – рабочих дней,    -Учредительные документы (ИНН, ОГРН, Устав)     -Заявка"
        },
        editorData: '<p>Content of the editor.</p>',
        editorConfig: {
          language: 'ru',
        },
        search: null,
        page: null,
        edit: null,
        id:   this.$route.params.id
      }
    },
    components: {
      DatePicker,
//      CKEditor
   
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl'
      }),
    
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'agreement/save',
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
         .then(() => {
            this.$router.push('/mybuisness')
        })
      },
       returnTo(){
        this.$router.push('../../mybuisness/')
      }
    },
    created() {
      this.setHeader('Cоздать Договор')
  
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

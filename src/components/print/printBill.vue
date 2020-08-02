<template>
<section class="row">
<div class="col-1" style=""></div>
<div class="col-10" style="">
    <div class="row">{{ client_id }} --{{ clients }}
        <div class="row">
            <div class="col-1" style=""></div>
            <div class="col-9" style="">
                    <a href="#" @click="returnTo()" ><img src="../../../static/images/iconBest/iconBill.png" ></a>
            </div>
            <div class="col-1" style=""></div>
        </div>

        <div class="col-xs-12" style="margin-top:20px;">
            <table class="table table-bordered">
                <tr>
                    <td colspan="4">ФИЛИАЛ "НИЖЕГОРОДСКИЙ" АО "АЛЬФА-БАНК" <br> г.Нижний Новгород</td>
                    <td>БИК</td>
                    <td>042202824</td>
                </tr>
                <tr>
                    <td colspan="4">Банк получателя</td>
                    <td>Сч. №</td>
                    <td>30101810200000000824</td>
                </tr>
                <tr>
                    <td>ИНН</td>
                    <td>5902241420</td>
                    <td>КПП</td>
                    <td>590401001</td>
                    <td rowspan="3">Сч. №</td>
                    <td rowspan="3">40702810429490001887</td>
                </tr>
                <tr>
                    <td colspan="4">Общество с ограниченной ответственностью "Современные решения"
                    </td>
                </tr>
                <tr>
                   <td colspan="4">Получатель</td>
                </tr>
            </table>

             <h3><b>Счет на оплату №  от  г.</b></h3>
             <hr>
            <div class="row">
               <div class="col-4"  style="text-align:left"> Поставщик<br>(Исполнитель):</div>
               <div class="col-8"><b>ООО «Современные решения», 
                   ИНН 5902241420, КПП 590201001, 614010, Пермский край, г. Пермь, ул. Куйбышева, 95Б, оф. 1806</b></div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-4"  style="text-align:left">Покупатель<br>(Заказчик):</div>
                <div class="col-8"><b>
                        , ИНН ,  
                        КПП </b></div>
            </div>
            <div class="row"  style="margin-top:20px;">
                <div class="col-4" style="text-align:left">Основание:</div>
                <div class="col-8" >Основной договор</div>
            </div>

            <div class="col-xs-12" style="margin-top:20px;"> 
               <table class="table table-bordered" >
                    <tr>
                        <th>№</th>
                        <th>Товары (работы, услуги)</th>
                        <th>Кол-во</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </table>
            </div>

            <div class="row justify-content-end" style="margin-top:20px;">
                <div class="col-2 ">
                    <br><b>Итого:</b>

                    <br><b>Без налога (НДС) </b>
                    <br><b>Всего к оплате: </b>
                </div>
            </div>
            <div class="row" style="margin-top:20px;">
               Всего наименований  , на сумму  руб.
            </div>
            <div class="row" style="margin-top:20px;">
                <b>1200p </b>
            </div>
            <div  class="row" style="margin-top: 20px">Оплатить не позднее <br>  
                Оплата данного счета означает согласие с условиями поставки товара.
                Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.
                Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и
                паспорта.
            </div>

            <hr>
            <br>
            <br>
            <div class="row" style="margin-top:20px;">
               

                <div class="col-6" style="" >Руководитель __________Филимонов А.Е.</div>
                <div class="col-6" style="">Бухгалтер ___________Веселкова С.В.</div>
            </div>

                <div class="row">
                    <div class="col-xs-6" style="margin-top:20px;">

                    <img class="img-responsive center-block" 
                            src="../../../static/images/iconBest/singdir.png" 
                            style="position:relative;
                                    z-index: 1; 
                                    top: -75px;height: 30px;margin-left: 15%;">	
                    </div>  
                    <div class="col-xs-6" style="margin-top:20px;">

                        <img class="img-responsive center-block" 
                            src="../../../static/images/iconBest/singbuh.png" 
                            style="position:relative;
                                    z-index: 1; 
                                    top: -75px;height: 30px;margin-left: 15%;">	
                    </div>
            </div>
            <div class="col-xs-12" style="margin-top:80px;">

            <div class="col-xs-5">
                <img class="img-responsive center-block" 
                src="../../../static/images/iconBest/stamp.png" 
                style="position:relative;
                        z-index: 1; 
                        top: -120px;height: 250px;">	 
            </div>

        <div class="col-xs-7">
        </div>

        </div>
        </div>
        </div>
</div>
<div class="col-1"></div>
</section>    
</template>

<script>
  import Vuex from 'vuex'
 export default {
    props: ['id'],
    name: 'citypek',
    data () {
      return {
        client_id: this.id.id,
        form:{},
      }
    },
     computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          clients: 'clients/datalist',
        }),
         ...Vuex.mapActions({
            save: 'comments/save',
            find: 'clients/findOnId',
            drop: 'comments/delete'
      })
    },
    methods: {
         findData(){
            this.find(this.client_id)  
        },
        returnTo(){
            this.$router.push('./components/mybuisness/')
        }
    },
      created() {
       this.findData()
    }
   
 }
</script>
















 

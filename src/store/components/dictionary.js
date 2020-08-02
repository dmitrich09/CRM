
const state = {
  yesNoList:
  [
    {id: 10, name: 'ДА'},
    {id: 20, name: 'НЕТ'},
  ],
  is_LprList:
  [
    {id: 10, name: 'ДА'},
    {id: 20, name: 'НЕТ'},
  ],
  clientType:
  [
    {id: 10, name: 'Холодный'},
    {id: 20, name: 'Теплый'},
    {id: 30, name: 'Действующий'},
    {id: 40, name: 'На удалении'}
  ],
  ABCList:
  [
    {id: 10, name: 'A'},
    {id: 20, name: 'B'},
    {id: 30, name: 'C'},
  ],
  statusTask:[
    {id: 10, name: 'В работе'},
    {id: 20, name: 'Готово'},
    {id: 30, name: 'Готово'},
  ],
  callsStatus:[
    {id: 10, name: 'В работе'},
    {id: 20, name: 'Отказ'},
    {id: 30, name: 'Переведен в лид'},
  ],
  callsShow:[
    {id: 10, name: 'Просмотрено'},
    {id: 20, name: 'Не просмотрено'},
  ],
  statusLead:[
    {id: 10, name: 'Не обработан'},
    {id: 20, name: 'Не дозвонился'},
    {id: 30, name: 'Не вышел на ЛПР'},
    {id: 40, name: 'ЛПР'},
    {id: 50, name: 'Отказ'},
    {id: 60, name: 'Успех'},
  ],
  statusAgreement:[
    {id: 10, name: 'Согласован'},
    {id: 20, name: 'Отказ'},
    {id: 30, name: 'Новый'}
  ],
  commentType:[
    {id: 10, name: 'Не дозвонился'},
    {id: 20, name: 'Не вышел на ЛПР'},  
    {id: 30, name: 'ЛПР'},
    {id: 40, name: 'Отказ'},
    {id: 50, name: 'Успех'},
  ],
  kpStatuses:[
    {id: 10, name: 'Составлено'},
    {id: 20, name: 'Отправлено'},
    {id: 30, name: 'Ценовые переговоры по КП'},  
    {id: 40, name: 'Договор'},
    {id: 50, name: 'Отмена'},
  ],
  timelineStatus:[
    {id: 10, name: 'Партия'},
    {id: 20, name: '1 год'},
    {id: 30, name: '2 годa'},  
    {id: 40, name: '3 годa'},
    {id: 50, name: '4 годa'},
    {id: 60, name: '5 лет'},
    {id: 70, name: 'Бессрочно'},
  ],
  statusOrk:[
    {id: 10, name: 'Сбор документов для запуска'},
    {id: 20, name: 'В запуске'},
    {id: 30, name: 'Согласование макета клиентом'},  
    {id: 40, name: 'Соглсование макета поставщиком'},
    {id: 50, name: 'Анализ документов поставщиком'},
    {id: 60, name: 'В печати'},
    {id: 70, name: 'На регистрации'},
    {id: 80, name: 'Оформление ПИ'},
    {id: 90, name: 'Согласование ПИ клиентом'},
    {id: 100, name: 'Согласование ПИ поставщиком'},
    {id: 110, name: 'В печати ПИ'},
    {id: 120, name: 'Доставка оригинала из органа'},
    {id: 130, name: 'Доставка оригинала клиенту'},
    {id: 140, name: 'Обзвон по качеству'},
    {id: 150, name: 'Завершен'},
    {id: 160, name: 'Отказ'},
  ],
 
  }

const getters = {

  statusAgreement: state => state.statusAgreement,
  clientType: state => state.clientType,
  ABCList: state => state.ABCList,
  callsStatus: state => state.callsStatus,
  statusTask: state => state.statusTask,
  statusLead: state => state.statusLead,    
  kpStatuses: state => state.kpStatuses,
  timelineStatus: state => state.timelineStatus,    
  yesNoList: state => state.yesNoList,
  is_LprList: state => state.is_LprList,
  statusOrk: state => state.statusOrk,
  
 
}

const actions = {
 
}


const mutations = {
 
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}

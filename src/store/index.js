import Vue from 'vue'
import Vuex from 'vuex'
import app from './admin/app'
import auth from './admin/auth'
import navigation from './admin/navigation'
import sphere from "./components/sphere"
import source from './components/source'
import city from './components/city'
import contacttype from './components/contacttype'
import document from './components/document'
import provider from './components/provider'
import declinematter from './components/declinematter'
import clients from './components/clients'
import task from './components/task'
import outcall from './components/outcall'
import lead from './components/lead'
import application from './components/application'  
import kp from './components/kp'
import agreement from './components/agreement'
import ork from './components/ork'
import mybuisness from './components/mybuisness'
import comments from './components/comments'
import dictionary from './components/dictionary'
import contacts from './components/contacts'
import calls from './components/calls'
import item from './components/item'
import reports from './components/reports'
import funnel from './components/funnel'
import plan from './components/plan'
import payment from './components/payment'
import search from './components/search'



Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'  

export default new Vuex.Store({
  modules: {
    app,
    auth,
    navigation,
    sphere,
    source,
    city,
    contacttype,
    document,
    provider,
    declinematter,
    clients,
    task,
    outcall,
    lead,
    application,
    kp,
    agreement,
    ork,
    mybuisness,
    comments,
    dictionary,
    contacts,
    calls,
    item,
    reports,
    funnel,
    plan,
    payment,
    search
 
  },
  strict: debug,
})

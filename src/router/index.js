import Vue from 'vue'
import store from '../store'
import Router from 'vue-router'
import Login from '../components/admin/login'
import Logout from '../components/admin/logout'
import Users from '../components/admin/users'
import Profile from '../components/admin/users/profile'
import ChangePassword from '../components/admin/users/changePassword'
import RightUser from '../components/admin/users/right'
import Registration from '../components/admin/registration'
import Restore from '../components/admin/restore'
import Activate from '../components/admin/activate'
import Sphere from '../components/sphere'
import Source from '../components/source'
import City from '../components/city'
import Contacttype from '../components/contacttype'
import Document from '../components/document'
import Provider from '../components/provider'
import Declinematter from '../components/declinematter'
import Clients from '../components/clients'
import Comments from '../components/comments'
import Mybuisness from '../components/mybuisness'
import OrkPayment from '../components/ork'
import Show from '../components/clients/show'
import AddClientsListForm from '../components/clients/addClientsListForm'
import CreateLead from '../components/create/createLead'
import CreateAppls from '../components/create/createAppls'
import CreateKp from '../components/create/createKp'
import CreateContract from '../components/create/createContract'
import CreateOrk from '../components/create/createOrk'
import PrintBill from '../components/print/printBill'
import PrintContract from '../components/print/printContract'
import showAppls from '../components/application/showAppls'
import DetalsApps from '../components/application/detals'            
import DetalsKp from '../components/kp/detals'          
import DetalsAgrem from '../components/agreement/detals'          
import DetalsPay from '../components/payment/detals'          
import Panel from '../components/clients/showComponent/panel'    
import Plan from '../components/reports/plan'
import Funnel from '../components/reports/funnel'
import Callreport from '../components/reports/callreport'
import Zadarmacalls from '../components/reports/zadarmacalls'   
import Converce from '../components/reports/converce'
import Payrep from '../components/reports/payrep'
import Modal from '../components/modal/modal'
import Search from '../components/search'
import Getdok from '../components/reports/getdok'


Vue.use(Router)

const ifNotAuthenticated = (to, from, next) => {
  if (store.state.auth.isAuthenticated !== true) {
    next()
    return
  }
  next('/login')
}

const ifAuthenticated = (to, from, next) => {
  if (store.state.auth.isAuthenticated === true) {
    next()
    return
  }
}


const check = (to, from, next) => {
  store.dispatch('auth/check')
  .then(() => {
    next()
  })
  .catch(error => {
    console.log('error: ', error)
    next('/login')
  })
  next()
}

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Users,
      beforeEnter: check, ifAuthenticated,
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/users',
      name: 'Users',
      component: Users,
      beforeEnter: check, ifAuthenticated,
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout,
    },
    {
      path: '/profile/:id',
      name: 'cliencardProfile',
      component: Profile,
      beforeEnter: check, ifAuthenticated,
    },
    {
      path: '/changepassword',
      name: 'changepassword',
      component: ChangePassword,
      beforeEnter: check, ifAuthenticated,
    },
    {
      path: '/rightsUser/:id',
      name: 'rightsUser',
      component: RightUser,
      beforeEnter: check, ifAuthenticated,
    },
    {
      path: '/registration',
      name: 'registration',
      component: Registration,
    },
    {
      path: '/restore',
      name: 'restore',
      component: Restore,
    },
    {
      path: '/activate/:key',
      name: 'activate',
      component: Activate,
    },
    {
      path: '/sphere',
      name: 'sphere',
      component: Sphere,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/source',
      name: 'source',
      component: Source,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/city',
      name: 'city',
      component: City,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/contacttype',
      name: 'contacttype',
      component: Contacttype,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/document',
      name: 'document',
      component: Document,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/provider',
      name: 'provider',
      component: Provider,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/declinematter',
      name: 'declinematter',
      component: Declinematter,
      beforeEnter: check, ifNotAuthenticated,
    },
    {
      path: '/clients',
      name: 'clients',
      component: Clients,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/mybuisness',
      name: 'mybuisness',
      component: Mybuisness,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/comments/:id',
      name: 'comments',
      component: Comments,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/clients/show/:id',
      name: 'show',
      component: Show,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/create/createLead/:id',
      name: 'CreateLead',
      component: CreateLead,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/create/createAppls/:id',
      name: 'createAppls',
      component: CreateAppls,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/create/createKp/:id',
      name: 'createKp',
      component: CreateKp,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/create/createContract/:id',
      name: 'createContract',
      component: CreateContract,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/create/createOrk/:id',
      name: 'createOrk',
      component: CreateOrk,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/ork/:id',
      name: 'OrkPayment',
      component: OrkPayment,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/printBill/:id',
      name: 'printBill',
      component: PrintBill,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/printContract/:id',
      name: 'printContract',
      component: PrintContract,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/application/showAppls/:id',
      name: 'showAppls',
      component: showAppls,
      beforeEnter: check, ifNotAuthenticated,    
    },
    {
      path: '/application/detals',
      name: 'DetalsApps',
      component: DetalsApps,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/kp/detals',
      name: 'DetalsKp',
      component: DetalsKp,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/agreement/detals',
      name: 'DetalsAgrem',
      component: DetalsAgrem,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/payment/detals',
      name: 'DetalsPay',
      component: DetalsPay,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/clients/showComponent/panel',
      name: 'Panel',
      component: Panel,
    },
    {
      path: '/clients/addClientsListForm',
      name: 'AddClientsListForm',
      component: AddClientsListForm,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/reports/plan',
      name: 'plan',
      component: Plan,
      beforeEnter: check, ifNotAuthenticated,      
    },
    {
      path: '/reports/funnel',
      name: 'funnel',
      component: Funnel,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/reports/callreport',
      name: 'callreport',
      component: Callreport,
      beforeEnter: check, ifNotAuthenticated,   
    },
    {
      path: '/reports/zadarmacalls',
      name: 'zadarmacalls',
      component: Zadarmacalls,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/reports/converce',
      name: 'converce',
      component: Converce,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/reports/payrep',
      name: 'payrep',
      component: Payrep,
      beforeEnter: check, ifNotAuthenticated,     
    },
    {
      path: '/reports/getdok',
      name: 'getdok',
      component: Getdok,
      beforeEnter: check, ifNotAuthenticated,     
    },
   
    {
      path: '/modal/modal',
      name: 'modal',
      component: Modal,
      beforeEnter: check, ifNotAuthenticated,      
    },
    {
      path: '/search',
      name: 'search',
      component: Search,
      beforeEnter: check, ifNotAuthenticated,   
    },
   

  ],
})

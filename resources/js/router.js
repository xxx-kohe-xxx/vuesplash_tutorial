import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインストールする
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

import store from './store'

// VueRouterプラグインを使う
// これによって<RouterView/>コンポーネントなどを使うことができる
Vue.use(VueRouter)

// pathとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: PhotoList
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next){
      if(store.getters['auth/check']){
        next('/') // 引数ありの場合 Login コンポーネントが生成される前に'/'へリダイレクトされる
      } else {
        next()  // そのままLoginコンポーネントへ切り替わる
      }
    }
  }
]

// VueRouterインスタンスを作成
const router = new VueRouter({
  mode:'history', // /#/という表示にならないようにする
  routes // マッピングしたroutesを入れる
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
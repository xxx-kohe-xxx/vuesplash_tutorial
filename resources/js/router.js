import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインストールする
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

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
    component: Login
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
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessage: null, // バリデーションエラーのときのエラーメッセージ (ログインフォーム)
  registerErrorMessage: null, // バリデーションエラーのときのエラーメッセージ (登録フォーム)
}

const getters = {
  check: state => !!state.user,
  username: state => state.user ? state.user.name: ""
}

const mutations = {
  setUser (state, user){
    state.user = user
  },
  setApiStatus (state, status){
    state.apiStatus = status
  },
  setLoginErrorMessage (state, messages){
    state.loginErrorMessage = messages
  },
  setRegisterErrorMessage (state, messages){
    state.registerErrorMessage = messages
  }
}

const actions = {
  // 会員登録
  async register (context, data){
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/register', data)

    if(response.status === CREATED){
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setRegisterErrorMessage', response.data.errors)
    }else{
      comtext.commit('error/setCode', response.status, {root:true})
    }
  },

  // ログイン
  async login (context, data){
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)

    if(response.status === OK){
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){ // バリデーションエラーのとき以下の処理をする
      context.commit('setLoginErrorMessage', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, { root: true})
    }
  },

  // ログアウト
  async logout (context){
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    if(response.status === OK){
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true})
  },

  // ログインユーザーチェック
  async currentUser(context){
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    const user = response.data || null

    if(response.status === OK){
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }

    context.commit('setApiStatus', false)    
    context.commit('error/setCode', response.status, { root: true})
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}


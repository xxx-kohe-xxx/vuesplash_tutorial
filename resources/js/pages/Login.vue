<template>
  <div class="container--small">
    <ul class="tab">
      <li 
        class="tab__item" 
        :class="{'tab__item--active': tab === 1}"
        @click="tab = 1"
      >Login</li>
      <li 
        class="tab__item" 
        @click="tab = 2"
        :class="{'tab__item--active': tab === 2}"
      >Register</li>
    </ul>
    <div class="panel" v-show="tab===1">
      <form action="" class="form" @submit.prevent="login">
        <div class="errors" v-if="loginErrors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg">{{msg}}</li>
          </ul>
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">{{msg}}</li>
          </ul>
        </div>
        <label for="login-email">Email</label>
        <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
        <label for="login-password">Password</label>
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
        <div class="form__button">
          <button type="submit" class="button button--inverse">login</button>
        </div>
      </form>
    </div>
    <div class="panel" v-show="tab===2">
      <form action="" class="form" @submit.prevent="register">
        <div class="errors" v-if="registerErrors">
          <ul v-if="registerErrors.name">
            <li v-for="msg in registerErrors.name" :key="msg">{{msg}}</li>
          </ul>
          <ul v-if="registerErrors.email">
            <li v-for="msg in registerErrors.email" :key="msg">{{msg}}</li>
          </ul>
          <ul v-if="registerErrors.password">
            <li v-for="msg in registerErrors.password" :key="msg">{{msg}}</li>
          </ul>
        </div>
        <label for="username">Name</label>
        <input type="text" class="form__item" id="username" v-model="registerForm.name">
        <label for="email">Email</label>
        <input type="text" class="form__item" id="email" v-model="registerForm.email">
        <label for="password">Password</label>
        <input type="password" class="form__item" id="password" v-model="registerForm.password">
        <label for="password-confirmation">Password (confirm)</label>
        <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
        <div class="form__button">
          <button type="submit" class="button button--inverse">regiser</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>

import { mapState } from 'vuex'

export default {
  data(){
    return {
      tab: 1,
      loginForm:{
        email:'',
        password:'',
      },
      registerForm:{
        name:'',
        email:'',
        password:'',
        password_confirmation:'',
      },
    }
  },
  methods: {
    async login(){
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm)
      // トップページに移動する
      if(this.apiStatus){
        this.$router.push('/')
      }
    },
    async register(){
      // authストアのregisterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      // トップページに移動する
      if(this.apiStatus){
        this.$router.push('/')
      }
    },
    clearError(){
      this.$store.commit('auth/setLoginErrorMessage', null)
      this.$store.commit('auth/setRegisterErrorMessage', null)
    }
  },
  created() {
    this.clearError()
  },
  computed: {
    apiStatus(){
      return this.$store.state.auth.apiStatus
    },
    loginErrors(){
      return this.$store.state.auth.loginErrorMessage
    },
    registerErrors(){
      return this.$store.state.auth.registerErrorMessage
    }

    // mapStateを使った場合
    // mapState({
    //   apiStatus: state => state.auth.apiStatus,
    //   loginErrorMessage: state => state.auth.loginErrorMessage
    // })
  }
}
</script>

<style>

</style>
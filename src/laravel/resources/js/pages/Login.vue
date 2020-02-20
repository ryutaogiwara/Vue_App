<template>
  <div class="container--small">
    <ul class="tab">
      <!-- 
        @clickでtabの状態切り替えを行う
        tabの状態に合わせて--activeクラスを付与し、style切り替えを行う
       -->
      <li class="tab__item" :class="{'tab__item--active': tab === 1}" @click="tab = 1">
        Login
      </li>

      <li class="tab__item" :class="{'tab__item--active': tab === 2}" @click="tab = 2">
        Register
      </li>
    </ul>
    <!-- v-showでtab状態を参照し、viewを切り替えているように見せる -->
    <div class="panel" v-show="tab === 1">Login Form</div>
    <div class="panel" v-show="tab === 2">Register Form</div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      tab: 1
    }
  }
}
</script>

<template>
  <div>
    <v-tabs v-model="active" color="cyan" dark slider-color="yellow" transition="slide-x-transition">
      <v-tab>All Task</v-tab>
      <v-tab-item>
        <v-card flat>
            <v-card-text> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat </v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab>My Task</v-tab>
      <v-tab-item >
        <v-card flat>
            <v-card-text>  Test Text </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
  export default {
    data () {
      return {
         active: ''
      }
    },
    methods: {

    }
  }
</script>
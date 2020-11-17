<template>
  <div>
    <el-steps :active="active" finish-status="success">
      <el-step title="Step 1">
        <h1>asdasdasdsad</h1>
      </el-step>
      <el-step title="Step 2"></el-step>
      <el-step title="Step 3"></el-step>
    </el-steps>
  <el-form v-if="active===0">
    <div class="row">
      <div class="col-sm-6">
        <el-input placeholder="Please input" v-model="input"></el-input>
      </div>
    </div>
  </el-form>
  <el-form v-if="active===1">
    <el-input placeholder="Please input atm" v-model="input"></el-input>
  </el-form>
    <div class="float-right">
      <el-button style="margin-top: 12px"  @click="next">Next step</el-button>
    </div>
    <div class="float-left">
       <el-button v-if="this.active > 0" style="margin-right: 12px" @click="back"
      >Back step</el-button
    </div>
   
    >
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: 0,
      input: "",
      form: {
        name: "",
        region: "",
        date1: "",
        date2: "",
        delivery: false,
        type: [],
        resource: "",
        desc: "",
      },
    };
  },

  methods: {
    onSubmit() {
      console.log("submit!");
    },
    next() {
      if (this.active++ > 2) this.active = 0;
    },
    back() {
      if (this.active-- > 2) this.active--;
    },
  },
};
</script>

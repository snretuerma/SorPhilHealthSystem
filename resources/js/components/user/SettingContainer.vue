<template>
  <div>
    <el-steps :active="active" finish-status="success">
      <el-step title="Medical and Non-Medical Distribution">
        <h1>asdasdasdsad</h1>
      </el-step>
      <el-step title="Step 2"></el-step>
      <el-step title="Step 3"></el-step>
    </el-steps>
<el-form :inline="true"  class="demo-form-inline">
  <el-card class="box-card" shadow="never" style="margin-top:20px;" v-if="active===0">
    <div class="row">
      <div class="col-sm-6">
         Medical Percentage
         <el-input placeholder="Please input atm" v-model="input"></el-input>
      </div>
      <div class="col-sm-6">
         Non-Medical Percentage
         <el-input placeholder="Please input atm" v-model="input"></el-input>
      </div>
    </div>
  </el-card>
    <div class="row" v-if="active===1">
      <div class="col-sm-6">
        Pooled Percentage
        <el-input placeholder="Please input" v-model="input"></el-input>
      </div>
      <div class="col-sm-6">
        Shared Percentage
        <el-input placeholder="Please input" v-model="input"></el-input>
      </div>
    </div>
    <div class="row" v-if="active===2">
      <div class="col-sm-6">
         Requesting
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
         Health Care
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
         Anesthesiologist
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
         Admitting
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
      </div>
      <div class="col-sm-6">
        Surgeon
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
         ER
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
         Co-Management
         <el-input placeholder="Please input atm 333" v-model="input"></el-input>
      </div>
    </div>
</el-form>
    <div class="float-right">
      <el-button style="margin-top: 12px"  @click="next">Next step</el-button>
    </div>
    <div class="float-left">
       <el-button v-if="this.active > 0" style="margin-top: 12px" @click="back"
      >Back step</el-button>
    </div>
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

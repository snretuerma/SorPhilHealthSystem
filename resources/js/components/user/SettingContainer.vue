<template>
  <div>
      <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-cogs"></i>&nbsp;&nbsp;Setting
                </span>
            </div>
        </div>
      <!-- End Header -->
    <el-steps :active="active" finish-status="success">
      <el-step title="Medical and Non-Medical Distribution"></el-step>
      <el-step title="Pooled and Shared Percentage"></el-step>
      <el-step title="Other Physician"></el-step>
    </el-steps>
    <el-form style="margin-top:20px;">
      <el-card shadow="never" v-if="active===0">
        <div class="row">
          <div class="col-sm-6">
            Medical Percentage
            <el-input placeholder="Medical" v-model="input"></el-input>
          </div>
          <div class="col-sm-6">
            Non-Medical Percentage
            <el-input placeholder="Non-Medical" v-model="input"></el-input>
          </div>
        </div>
      </el-card>
      <el-card shadow="never" v-if="active===1">
        <div class="row">
          <div class="col-sm-6">
            Pooled Percentage
            <el-input placeholder="Pooled" v-model="input"></el-input>
          </div>
          <div class="col-sm-6">
            Shared Percentage
            <el-input placeholder="Shared" v-model="input"></el-input>
          </div>
        </div>
      </el-card>
      <el-card shadow="never" v-if="active===2">
        <div class="row">
          <div class="col-sm-6">
            Requesting
            <el-input placeholder="Requesting" v-model="input"></el-input>
            Health Care
            <el-input placeholder="HealthCare" v-model="input"></el-input>
            Anesthesiologist
            <el-input placeholder="Anesthesiologist" v-model="input"></el-input>
            Admitting
            <el-input placeholder="Admitting" v-model="input"></el-input>
          </div>
          <div class="col-sm-6">
            Surgeon
            <el-input placeholder="Surgeon" v-model="input"></el-input>
            ER
            <el-input placeholder="ER" v-model="input"></el-input>
            Co-Management
            <el-input placeholder="Co-Management" v-model="input"></el-input>
            <div class="float-right">
            <el-button type="info" style="margin-top: 12px" >Cancel</el-button>
            <el-button type="primary" style="margin-top: 12px" >Save Changes</el-button>
            </div>
          </div>
        </div>
      </el-card>
    </el-form>
    <div class="float-right">
      <el-button style="margin-top: 20px;margin-left:4px;" v-if="this.active < 2" @click="next">Next Step</el-button>
    </div>
    <div class="float-right">
       <el-button v-if="this.active > 0" style="margin-top: 20px" @click="back">Previous</el-button>
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

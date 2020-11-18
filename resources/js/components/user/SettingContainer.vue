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
      <div class="row col-12" style="padding:0px;margin-bottom:20px;">
      <div class="float-right" style="width:100%;text-align:right">
        <el-button @click="editStatus=false">Cancel</el-button>
        <el-button type="success" @click="editStatus=true">Edit</el-button>
      </div>
      </div>
      
      <!--Step Bar-->
      <el-steps :active="active" finish-status="success">
        <el-step title="Medical and Non-Medical Distribution"></el-step>
        <el-step title="Pooled and Shared Percentage"></el-step>
        <el-step title="Other Physician"></el-step>
      </el-steps>
      <!--End Step Bar-->
      
      <!--Step Form-->
      <el-form :model="form" ref="form" style="margin-top:20px;">
        <el-card shadow="never" v-if="active===0">
          <div class="row">
            <div class="col-sm-6">
                Medical Percentage
                <el-input placeholder="Medical" 
                          :readonly="!editStatus"
                          v-model="form.medical"
                          :clearable = true
                          @input.native="autoFillOtherTextBox($event,'stepOne','Medical')"
                        
                ></el-input>
            </div>
            <div class="col-sm-6">
                Non-Medical Percentage
                <el-input placeholder="Non-Medical" 
                          :readonly="!editStatus"
                          v-model="form.nonmedical"
                          :clearable = true
                          @input.native="autoFillOtherTextBox($event,'stepOne','Non-Medical')"
                ></el-input>
            </div>
          </div>
        </el-card>
        <el-card shadow="never" v-if="active===1">
          <div class="row">
            <div class="col-sm-6">
                Pooled Percentage
                <el-input placeholder="Pooled" 
                          :readonly="!editStatus"
                          v-model="form.pooled"
                          :clearable = true
                          @input.native="autoFillOtherTextBox($event,'stepTwo','Pooled')"
                ></el-input>
            </div>
            <div class="col-sm-6">
                Shared Percentage
                <el-input placeholder="Shared" 
                          :readonly="!editStatus"
                          v-model="form.shared"
                          :clearable = true
                          @input.native="autoFillOtherTextBox($event,'stepTwo','Shared')"
                ></el-input>
            </div>
          </div>
        </el-card>
        <el-card shadow="never" v-if="active===2">
          <div class="row">
              <div class="col-sm-6">
                  Requesting
                  <el-input placeholder="Requesting" 
                            :readonly="!editStatus"
                            v-model="form.requesting"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','Requesting')"
                  ></el-input>
                  Health Care
                  <el-input placeholder="HealthCare" 
                            :readonly="!editStatus"
                            v-model="form.healthcare"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','HealthCare')"
                  ></el-input>
                  Anesthesiologist
                  <el-input placeholder="Anesthesiologist" 
                            :readonly="!editStatus"
                            v-model="form.anesthesiologist"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','Anesthesiologist')"
                  ></el-input>
                  Admitting
                  <el-input placeholder="Admitting" 
                            :readonly="!editStatus"
                            v-model="form.admitting"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','Admitting')"
                  ></el-input>
              </div>
              <div class="col-sm-6">
                  Surgeon
                  <el-input placeholder="Surgeon" 
                            :readonly="!editStatus"
                            v-model="form.surgeon"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','Surgeon')"
                  ></el-input>
                  ER
                  <el-input placeholder="ER" 
                            :readonly="!editStatus"
                            v-model="form.er"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','ER')"
                  ></el-input>
                  Co-Management
                  <el-input placeholder="Co-Management" 
                            :readonly="!editStatus"
                            v-model="form.comanagement"
                            :clearable = true
                            @input.native="autoFillOtherTextBox($event,'stepTree','Co-Management')"
                  ></el-input>
                  <div class="float-right">
                      <el-button type="info" style="margin-top: 12px" @click="editStatus=false" >Cancel</el-button>
                      <el-button type="primary" style="margin-top: 12px" :disabled="!editStatus" @click="saveChanges()" >Save Changes</el-button>
                  </div>
              </div>
          </div>
        </el-card>
      </el-form>
      <!--End Step Form-->
       
      <!--Step Navigation-->
      <div class="float-right">
        <el-button style="margin-top: 20px;margin-left:4px;" v-if="this.active < 2" @click="next">Next Step</el-button>
      </div>
      <div class="float-right">
        <el-button v-if="this.active > 0" style="margin-top: 20px" @click="back">Previous</el-button>
      </div>
      <!--End Step Navigation-->
   

  </div>
</template>

<script>
export default {
  data() {
    return {
      active: 0,
      editStatus: false,
      form: {
        medical: "50",
        nonmedical: "50",
        pooled: "30",
        shared: "70",
        requesting: "10",
        healthcare: "10",
        anesthesiologist: "30",
        admitting: "10",
        surgeon: "10",
        er: "10",
        comanagement: "20",
      },
    };
  },

  methods: {
    saveChanges() {
        console.log("submit!");
    },
    next() {
        if (this.active++ > 2) this.active = 0;
    },
    back() {
        if (this.active-- > 2) this.active--;
    },
    autoFillOtherTextBox(eventValue,step,textboxId){
        switch (step) {
          case "stepOne":
            console.log(eventValue.target.value + "--" + step + "--" + textboxId);
            break;
          case "stepTwo":
            console.log(eventValue.target.value + "--" + step + "--" + textboxId);
            break;
          default:
            console.log(eventValue.target.value + "--" + step + "--" + textboxId);
        }
    },
    integerOnly ($event){
        let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
        if ((keyCode < 48 || keyCode > 57)  ) {
            $event.preventDefault();
        }
    }
  },
  created(){
      window.addEventListener('keypress', this.integerOnly);
  },
};
</script>

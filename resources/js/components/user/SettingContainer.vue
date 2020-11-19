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
        <el-button @click="editStatus=false;saveBtn=false;">Cancel</el-button>
        <el-button type="success" @click="editStatus=true;saveBtn=true;">Edit</el-button>
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
                Medical
                <el-input placeholder="Medical" 
                          :disabled="!editStatus"
                          v-model="form.medical"
                          @input.native="autoFillOtherTextBox($event,'stepOne','Medical')"
                ></el-input>
            </div>
            <div class="col-sm-6">
                Non-Medical
                <el-input placeholder="Non-Medical" 
                          :disabled="!editStatus"
                          v-model="form.nonmedical"
                          @input.native="autoFillOtherTextBox($event,'stepOne','Non-Medical')"
                ></el-input>
            </div>
          </div>
        </el-card>
        <el-card shadow="never" v-if="active===1">
          <div class="row">
            <div class="col-sm-6">
                Pooled
                <el-input placeholder="Pooled" 
                          :disabled="!editStatus"
                          v-model="form.pooled"
                          @input.native="autoFillOtherTextBox($event,'stepTwo','Pooled')"
                ></el-input>
            </div>
            <div class="col-sm-6">
                Shared
                <el-input placeholder="Shared" 
                          :disabled="!editStatus"
                          v-model="form.shared"
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
                            :disabled="!editStatus"
                            v-model="form.requesting"
                            @input.native="autoFillOtherTextBox($event,'stepTree','Requesting')"
                  ></el-input>
                  Health Care
                  <el-input placeholder="HealthCare" 
                            :disabled="!editStatus"
                            v-model="form.healthcare"
                            @input.native="autoFillOtherTextBox($event,'stepTree','HealthCare')"
                  ></el-input>
                  Anesthesiologist
                  <el-input placeholder="Anesthesiologist" 
                            :disabled="!editStatus"
                            v-model="form.anesthesiologist"
                            @input.native="autoFillOtherTextBox($event,'stepTree','Anesthesiologist')"
                  ></el-input>
                  Admitting
                  <el-input placeholder="Admitting" 
                            :disabled="!editStatus"
                            v-model="form.admitting"
                            @input.native="autoFillOtherTextBox($event,'stepTree','Admitting')"
                  ></el-input>
              </div>
              <div class="col-sm-6">
                  Surgeon
                  <el-input placeholder="Surgeon" 
                            :disabled="!editStatus"
                            v-model="form.surgeon"
                            @input.native="autoFillOtherTextBox($event,'stepTree','Surgeon')"
                  ></el-input>
                  ER
                  <el-input placeholder="ER" 
                            :disabled="!editStatus"
                            v-model="form.er"
                            @input.native="autoFillOtherTextBox($event,'stepTree','ER')"
                  ></el-input>
                  Co-Management
                  <el-input placeholder="Co-Management" 
                            :disabled="!editStatus"
                            v-model="form.comanagement"
                            @input.native="autoFillOtherTextBox($event,'stepTree','Co-Management')"
                  ></el-input>
                  <div class="float-right">
                      <el-button type="primary" style="margin-top: 12px" :disabled="!saveBtn" @click="saveChanges()" >Save Changes</el-button>
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
          saveBtn: false,
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
        this.$confirm('Are you sure you want to save your changes?', 'Confirm Changes', {
            confirmButtonText: 'SAVE CHANGES',
            cancelButtonText: 'Cancel',
            type: 'warning'
        }).then(() => {
            axios
              .post("update_setting", this.form)
              .then(response => {
                  if (response.status > 199 && response.status < 203) {
                     this.open_notif("success", "Updated", "Setting changes has been save/updated");
                  }
              })
              .catch(error => {
                  this.errors = error.response.data.errors;
                  this.open_notif("error", "Error", "Failed to update setting");
              });
        }).catch(() => {
            this.open_notif("info", "No Changes", "Changes not save");     
        });
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
                if(textboxId == "Medical"){
                    if(eventValue.target.value > 100){
                        this.open_notif("warning", "Invalid", "Input not greater than 100%");
                        this.form.medical = 100;
                        this.form.nonmedical = 0;
                    }else{
                        var computed = 100 - parseInt(this.form.medical);
                        if(computed >= 0 && computed <= 100){
                            this.form.nonmedical = computed;
                        }else{
                            this.form.nonmedical = 100;
                        }
                    }
                }else{
                    if(eventValue.target.value > 100){
                        this.open_notif("warning", "Invalid", "Input not greater than 100%");
                        this.form.nonmedical = 100;
                        this.form.medical = 0;
                    }else{
                        var computed = 100 - parseInt(this.form.nonmedical);
                        if(computed >= 0 && computed <= 100){
                            this.form.medical = computed;
                        }else{
                            this.form.medical = 100;
                        }
                    }
                }
            break;
          case "stepTwo":
                if(textboxId == "Pooled"){
                    if(eventValue.target.value > 100){
                        this.open_notif("warning", "Invalid", "Input not greater than 100%");
                        this.form.pooled = 100;
                        this.form.shared = 0;
                    }else{
                        var computed = 100 - parseInt(this.form.pooled);
                        if(computed >= 0 && computed <= 100){
                            this.form.shared = computed;
                        }else{
                            this.form.shared = 100;
                        }
                    }
                }else{
                    if(eventValue.target.value > 100){
                        this.open_notif("warning", "Invalid", "Input not greater than 100%");
                        this.form.shared = 100;
                        this.form.pooled = 0;
                    }else{
                        var computed = 100 - parseInt(this.form.shared);
                        if(computed >= 0 && computed <= 100){
                            this.form.pooled = computed;
                        }else{
                            this.form.pooled = 100;
                        }
                    }
                }
            break;
          default:
                var requesting = this.form.requesting;
                var healthcare = this.form.healthcare;
                var anesthesiologist = this.form.anesthesiologist;
                var admitting = this.form.admitting;
                var surgeon = this.form.surgeon;
                var er = this.form.er;
                var comanagement = this.form.comanagement;

                switch (textboxId) {
                  case "Requesting":
                       if(eventValue.target.value > 100){
                            this.form.requesting = "";
                       }else{
                            var computed = this.int(healthcare) + 
                                           this.int(anesthesiologist) + 
                                           this.int(admitting) + 
                                           this.int(surgeon) + 
                                           this.int(er) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(requesting > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.requesting = "";
                            }else if(requesting == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "HealthCare":
                       if(eventValue.target.value > 100){
                            this.form.healthcare = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(anesthesiologist) + 
                                           this.int(admitting) + 
                                           this.int(surgeon) + 
                                           this.int(er) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(healthcare > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.healthcare = "";
                            }else if(healthcare == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "Anesthesiologist":
                       if(eventValue.target.value > 100){
                            this.form.anesthesiologist = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(healthcare) + 
                                           this.int(admitting) + 
                                           this.int(surgeon) + 
                                           this.int(er) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(anesthesiologist > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.anesthesiologist = "";
                            }else if(anesthesiologist == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "Admitting":
                       if(eventValue.target.value > 100){
                            this.form.admitting = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(healthcare) + 
                                           this.int(anesthesiologist) + 
                                           this.int(surgeon) + 
                                           this.int(er) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(admitting > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.admitting = "";
                            }else if(admitting == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "Surgeon":
                       if(eventValue.target.value > 100){
                            this.form.surgeon = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(healthcare) + 
                                           this.int(anesthesiologist) + 
                                           this.int(admitting) + 
                                           this.int(er) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(surgeon > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.surgeon = "";
                            }else if(surgeon == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "ER":
                       if(eventValue.target.value > 100){
                            this.form.er = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(healthcare) + 
                                           this.int(anesthesiologist) + 
                                           this.int(admitting) + 
                                           this.int(surgeon) + 
                                           this.int(comanagement);
                            var availableValue = 100 - this.int(computed);
                            if(er > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.er = "";
                            }else if(er == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                  case "Co-Management":
                       if(eventValue.target.value > 100){
                            this.form.comanagement = "";
                       }else{
                            var computed = this.int(requesting) + 
                                           this.int(healthcare) + 
                                           this.int(anesthesiologist) + 
                                           this.int(admitting) + 
                                           this.int(surgeon) + 
                                           this.int(er);
                            var availableValue = 100 - this.int(computed);
                            if(comanagement > availableValue){
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                                this.form.comanagement = "";
                            }else if(comanagement == availableValue){
                                this.open_notif("success", "Valid", "Total percentage 100%");
                            }else{
                                this.open_notif("warning", "Invalid", "Available value to sum 100% is  '" + availableValue + "%'");
                            }
                       }
                    break;
                }
        }
        var stepOne = this.int(this.form.medical) + this.int(this.form.nonmedical);
        var stepTwo = this.int(this.form.pooled) + this.int(this.form.shared);
        var stepTree = this.int(this.form.requesting) +
                       this.int(this.form.healthcare) +
                       this.int(this.form.anesthesiologist) +
                       this.int(this.form.admitting) +
                       this.int(this.form.surgeon) +
                       this.int(this.form.er) +
                       this.int(this.form.comanagement);
        if(stepTree == 100 && stepTwo == 100 && stepTree == 100){
            this.saveBtn = true;
        }else{
            this.saveBtn = false;
        }
    },
    int(param)
    {
        if(isNaN(parseInt(param))){
            param = 0;
            return param;
        }else{
            if(param >= 0 && param <= 100){
                param = parseInt(param);
            }else{
                param = 0;
            }
            return Number(param);
        }
    },
    open_notif: function(status, title, message) {
        if (status == "success") {
            this.$notify.success({ title: title, message: message, offset: 0 });
        } else if (status == "error") {
            this.$notify.error({ title: title, message: message, offset: 0 });
        } else if (status == "info") {
            this.$notify.info({ title: title, message: message, offset: 0 });
        } else if (status == "warning") {
            this.$notify.warning({ title: title, message: message, offset: 0 });
        }
    },
    integerOnly ($event){
        let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
        if (keyCode < 48 || keyCode > 57) {
            $event.preventDefault();
        }
    }
  },
  created(){
      window.addEventListener('keypress', this.integerOnly);
  },
};
</script>

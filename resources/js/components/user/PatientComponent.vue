<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Patient List</h2>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-10" align="left">
        <div style="margin-bottom: 10px">
          <el-row>
            <el-col :span="10">
              <el-input
                v-model="filters[0].value"
                placeholder="Search"
              ></el-input>
            </el-col>
          </el-row>
        </div>
      </div>
      <div class="col-sm-2" align="right">
        <el-button type="primary" @click="formDialog('insert_data')"
          >Add</el-button
        >
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <data-tables
          :data="data"
          :page-size="10"
          :filters="filters"
          :pagination-props="{ pageSizes: [10, 20, 50] }"
          :action-col="actionCol"
        >
          <div slot="empty">Table Empty</div>
          <el-table-column
            v-for="title in titles"
            :prop="title.prop"
            :label="title.label"
            :key="title.label"
            sortable="custom"
          >
          </el-table-column>
          <p slot="append"></p>
        </data-tables>
        <el-dialog
          title="Patient Details"
          :visible.sync="dialogFormVisible"
          top="0vh"
        >
          <el-form :model="form">
            <el-form-item label="Lastname" :label-width="formLabelWidth">
              <el-input v-model="form.last_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Firstname" :label-width="formLabelWidth">
              <el-input v-model="form.first_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Middlename" :label-width="formLabelWidth">
              <el-input
                v-model="form.middle_name"
                autocomplete="off"
              ></el-input>
            </el-form-item>
            <el-form-item label="Suffix" :label-width="formLabelWidth">
              <el-input
                v-model="form.name_suffix"
                autocomplete="off"
              ></el-input>
            </el-form-item>
            <el-form-item label="Sex" :label-width="formLabelWidth">
              <el-select v-model="form.sex" placeholder="Please select">
                <el-option label="Male" value="1"></el-option>
                <el-option label="Female" value="2"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Birthdate" :label-width="formLabelWidth">
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.birthdate"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="Marital Status" :label-width="formLabelWidth">
              <el-select
                v-model="form.marital_status"
                placeholder="Please select"
              >
                <el-option label="Single" value="0"></el-option>
                <el-option label="Married" value="1"></el-option>
                <el-option label="Divorced" value="2"></el-option>
                <el-option label="Widowed" value="3"></el-option>
                <el-option label="Others/Prefer Not to Say" value="4"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="PhilHealth No." :label-width="formLabelWidth">
              <el-input
                v-model="form.philhealth_number"
                autocomplete="off"
              ></el-input>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button v-if="this.form.formmode == 'insert_data'" type="primary" @click="addPatient()">Save</el-button>
            <el-button v-if="this.form.formmode == 'edit_data'" type="primary" @click="editPatient()">Save Changes</el-button>
          </span>
        </el-dialog>
      </div>
    </div>
  </div>
</template>


<script>
"use strict";
export default {
  data() {
    return {
      data: [],
      patientinfo: [],
      filters: [
        {
          prop: ["first_name", "last_name", "philhealth_number", "middle_name"],
          value: "",
        },
      ],
      titles: [
        {
          prop: "name",
          label: "Name",
        },
        {
          prop: "sex",
          label: "Sex",
        },
        {
          prop: "birthdate",
          label: "Birthdate",
        },
        {
          prop: "marital_status",
          label: "Marital Status",
        },
        {
          prop: "philhealth_number",
          label: "PhilHealth #",
        },
      ],
      gridData: [
        {
          name: "",
          sex: "",
          birthdate: "",
          marital_status: "",
          philhealth_number: "",
        },
      ],
      actionCol: {
        label: "Actions",
        props: {
          align: "center",
        },
        buttons: [
          {
            props: {
              type: "info",
              icon: "el-icon-info",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
              this.dialogFormVisible = true;
              this.gridData[0].name = this.buildName(
                row.first_name,
                row.middle_name,
                row.last_name,
                row.name_suffix
              );
              this.gridData[0].sex = row.sex;
              this.gridData[0].birthdate = row.birthdate;
              this.gridData[0].marital_status = row.marital_status;
              this.gridData[0].philhealth_number = row.philhealth_number;
            },
          },
          {
            props: {
              type: "primary",
              icon: "el-icon-edit",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
              this.resetForm();
              this.form.id = row.id;
              this.form.formmode = "edit_data";

              this.form.last_name = row.last_name;
              this.form.first_name = row.first_name;
              this.form.middle_name = row.middle_name;
              this.form.name_suffix = row.name_suffix;
              this.form.sex = row.sex;
              this.form.birthdate = row.birthdate;
              this.form.marital_status = row.marital_status;
              this.form.philhealth_number = row.philhealth_number;

              this.form.edit_object_index = this.data.indexOf(row);

              this.form_check.last_name = row.last_name,
              this.form_check.first_name = row.first_name,
              this.form_check.middle_name = row.middle_name,
              this.form_check.name_suffix = row.name_suffix,
              this.form_check.sex = row.sex,
              this.form_check.birthdate = row.birthdate,
              this.form_check.marital_status = row.marital_status,
              this.form_check.philhealth_number = row.philhealth_number,

              this.form_check.name =
                  this.form_check.last_name +
                  ", " +
                  this.form_check.name_suffix +
                  " " +
                  this.form_check.first_name +
                  " " +
                  this.form_check.middle_name.slice(0, 1) +
                  ". ";
              this.formDialog('edit_data');
            },
          },
          {
            props: {
              type: "danger",
              icon: "el-icon-delete",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
              var data = this.data;

              this.deletePatients(row.id, (res_value) => {
                if (res_value) {
                  data.splice(data.indexOf(row), 1);
                }
              });
            },
          },
        ],
      },
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        last_name: "",
        first_name: "",
        middle_name: "",
        name_suffix: "",
        sex: "",
        birthdate: "",
        marital_status: "",
        philhealth_number: "",
        name: "",
        formmode: "",
        edit_object_index: ""
      },
      form_check: {
        last_name: "",
        first_name: "",
        middle_name: "",
        name_suffix: "",
        sex: "",
        birthdate: "",
        marital_status: "",
        philhealth_number: "",
        name: ""
      },
      formLabelWidth: "120px",
    };
  },
  methods: {
    addPatient: async function () {
      axios
        .post("add_patient", this.form)
        .then((response) => {
          if (this.form.sex == 1) {
            this.form.sex = "Male";
          } else if (this.form.sex == 2) {
            this.form.sex = "Female";
          } else if (this.form.sex == 3) {
            this.form.sex = "Not Applicable";
          } else {
            this.form.sex = "Not Known";
          }
          if (this.form.marital_status == 0) {
            this.form.marital_status = "Single";
          } else if (this.form.marital_status == 1) {
            this.form.marital_status = "Married";
          } else if (this.form.marital_status == 2) {
            this.form.marital_status = "Divorced";
          } else if (this.form.marital_status == 3) {
            this.form.marital_status = "Widowed";
          } else {
            this.form.marital_status = "Others/Prefer Not to Say";
          }
          this.form.name =
            this.form.last_name +
            ", " +
            this.form.name_suffix +
            " " +
            this.form.first_name +
            " " +
            this.form.middle_name.slice(0, 1) +
            ". ";
          this.data.push(this.form);

          console.log(this.form.name_suffix);
          this.dialogFormVisible = false;
        })
        .catch(function (error) {});
    },
    resetForm: function (){
        this.form.last_name = "";
        this.form.first_name = "";
        this.form.middle_name = "";
        this.form.name_suffix = "";
        this.form.sex = "";
        this.form.birthdate = "";
        this.form.marital_status = "";
        this.form.philhealth_number = "";
    },
    formDialog: function (id) {
      if(id == "insert_data"){
        this.form.formmode = "insert_data";
        this.resetForm();
        this.dialogFormVisible = true;
      }else if(id == "edit_data"){
        this.dialogFormVisible = true;
      }
    },
    open_notif: function (status, title, message) {
        if(status == "success"){
            this.$notify.success({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "error"){
            this.$notify.error({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "info"){
            this.$notify.info({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "warning"){
            this.$notify.warning({
              title: title,
              message: message,
              offset: 0
            });
        }
    },
    editPatient: function () {
        var _this = this;
        if(this.form.last_name == this.form_check.last_name && 
          this.form.first_name == this.form_check.first_name && 
          this.form.middle_name == this.form_check.middle_name && 
          this.form.name_suffix == this.form_check.name_suffix && 
          this.form.sex == this.form_check.sex &&
          this.form.birthdate == this.form_check.birthdate && 
          this.form.marital_status == this.form_check.marital_status &&
          this.form.philhealth_number == this.form_check.philhealth_number){
          _this.open_notif('info', 'Message', 'No Changes');
        }else{
            if(this.form.sex=="Male"){
              this.form.sex=1;
            }else if(this.form.sex=="Female"){
              this.form.sex=2;
            }
            if(this.form.marital_status=="Single"){
              this.form.marital_status=0;
            }else if(this.form.marital_status=="Married"){
              this.form.marital_status=1;
            }else if(this.form.marital_status=="Divorced"){
              this.form.marital_status=2;
            }else if(this.form.marital_status=="Widowed"){
              this.form.marital_status=3;
            }else if(this.form.marital_status=="Others/Prefer Not to Say"){
              this.form.marital_status=4;
            }
            _this.form.name =
                _this.form.last_name +
                ", " +
                _this.form.name_suffix +
                " " +
                _this.form.first_name +
                " " +
                _this.form.middle_name.slice(0, 1) +
                ". ";
            axios
              .post("patients_edit/" + this.form.id , this.form)
              .then((response) => {
                if (response.status > 199 && response.status < 203) {
                    _this.open_notif('success', 'Success', 'Changes has been saved');
                    this.dialogFormVisible = false;
                    if(_this.form.sex == 1){
                      _this.form.sex = "Male";
                    }else if(_this.form.sex == 2){
                      _this.form.sex = "Female";
                    }
                    if(_this.form.marital_status==0){
                      _this.form.marital_status="Single";
                    }else if(_this.form.marital_status==1){
                      _this.form.marital_status="Married";
                    }else if(_this.form.marital_status==2){
                      _this.form.marital_status="Divorced";
                    }else if(_this.form.marital_status==3){
                      _this.form.marital_status="Widowed";
                    }else if(_this.form.marital_status==4){
                      _this.form.marital_status="Others/Prefer Not to Say";
                    }
                    _this.data[parseInt(_this.form.edit_object_index)].last_name = _this.form.last_name;
                    _this.data[parseInt(_this.form.edit_object_index)].first_name = _this.form.first_name;
                    _this.data[parseInt(_this.form.edit_object_index)].middle_name = _this.form.middle_name;
                    _this.data[parseInt(_this.form.edit_object_index)].name_suffix = _this.form.name_suffix;
                    _this.data[parseInt(_this.form.edit_object_index)].sex = _this.form.sex;
                    _this.data[parseInt(_this.form.edit_object_index)].birthdate = _this.form.birthdate;
                    _this.data[parseInt(_this.form.edit_object_index)].marital_status = _this.form.marital_status;
                    _this.data[parseInt(_this.form.edit_object_index)].philhealth_number = _this.form.philhealth_number;
                    _this.data[parseInt(_this.form.edit_object_index)].name = _this.form.name;
                }
              })
              .catch(function (error) {});
        }
    },
    buildName: function (first_name, middle_name, last_name, suffix) {
      return (
        last_name +
        " " +
        suffix +
        ", " +
        first_name +
        " " +
        middle_name.slice(0, 1) +
        "."
      ).trim();
    },
    assignSex: function (sex_value) {
      var sex;
      switch (sex_value) {
        case 1:
          sex = "Male";
          break;
        case 2:
          sex = "Female";
          break;
        case 9:
          sex = "Not Applicable";
          break;
        default:
          sex = "Not Known";
      }
      return sex;
    },
    assignMaritalStatus: function (marital_status_value) {
      var marital_status;
      switch (marital_status_value) {
        case 0:
          marital_status = "Single";
          break;
        case 1:
          marital_status = "Married";
          break;
        case 2:
          marital_status = "Divorced";
          break;
        case 3:
          marital_status = "Widowed";
          break;
        default:
          marital_status = "Others/Prefer Not to Say";
      }
      return marital_status;
    },
    buildPatientData: function (element) {
      if (element.name_suffix == undefined) {
        element.name_suffix = "";
      }
      element.name = this.buildName(
        element.first_name,
        element.middle_name,
        element.last_name,
        element.name_suffix
      );
      element.sex = this.assignSex(element.sex);
      element.marital_status = this.assignMaritalStatus(element.marital_status);
    },
    getPatients: function () {
      axios
        .get("patients_get")
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPatientData(element);
          });
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    deletePatients: function (id, res) {
      this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        type: "warning",
      })
        .then(() => {
          var _this = this;
          axios.post("patients_delete/" + id).then(function (response) {
            if (response.status > 199 && response.status < 203) {
              _this.$message({
                type: "warning",
                message: "Succesfully! Deleted",
              });
              res(id);
            }
          });
        })
        .catch((action) => {
          this.$message({
            type: "success",
            message: action === "cancel" ? "Canceled" : "No changes",
          });
        });
    },
  },
  mounted() {
    this.getPatients();
  },
};
</script>

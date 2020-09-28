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
        <el-button type="primary" @click="dialogFormVisible = true"
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
                <el-option
                  label="Others/Prefer Not to Say"
                  value="4"
                ></el-option>
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
            <el-button type="primary" @click="addPatient()">Confirm</el-button>
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
              this.form.id = row.id;
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
    editPatient: function () {
      axios
        .post("edit_patient" , this.form)
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPatientData(element);
          });
          this.patientinfo = response.data;
          console.log(response.data);
        })
        .catch(function (error) {});
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
        case 2:
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

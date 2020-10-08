<template>
  <div>
    <!-- Header -->
    <div class="row">
      <div class="col-sm-12">
        <h2>Patient List</h2>
      </div>
    </div>
    <hr />
    <!-- End Header -->

    <div class="row">
      <!-- Search Box -->
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
      <!-- End Search Box -->

      <!-- Add Button -->
      <div class="col-sm-2" align="right">
        <el-button
          type="primary"
          @click="
            dialogFormVisible = true;
            form.formmode = 'add';
            clearFields();
          "
          >Add</el-button
        >
      </div>
      <!-- End Button -->
    </div>

    <!-- Card Begins Here -->
    <div class="card">
      <div class="card-body">
        <!-- Data table -->
        <data-tables
          :data="data"
          :page-size="10"
          :filters="filters"
          :pagination-props="{ pageSizes: [10, 20, 50] }"
          :action-col="actionCol"
          v-loading="loading"
        >
          <div slot="empty">Table Empty</div>
          <el-table-column
            v-for="title in titles"
            :prop="title.prop"
            :label="title.label"
            :key="title.label"
            :width="title.width"
            sortable="custom"
          >
          </el-table-column>
          <p slot="append"></p>
        </data-tables>
        <!-- Data table ends -->

        <!-- Add Patient form -->
        <el-dialog
          title="Patient Details"
          :visible.sync="dialogFormVisible"
          top="5vh"
          :close-on-press-escape="false"
          :close-on-click-modal="false"
        >
          <el-form :model="form" :rules="rules" ref="form">
            <el-form-item
              label="Lastname"
              :label-width="formLabelWidth"
              prop="last_name"
            >
              <el-input v-model="form.last_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item
              label="Firstname"
              :label-width="formLabelWidth"
              prop="first_name"
            >
              <el-input v-model="form.first_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item
              label="Middlename"
              :label-width="formLabelWidth"
              prop="middle_name"
            >
              <el-input
                v-model="form.middle_name"
                autocomplete="off"
              ></el-input>
            </el-form-item>
            <el-form-item
              label="Suffix"
              :label-width="formLabelWidth"
              prop="name_suffix"
            >
              <el-input
                v-model="form.name_suffix"
                autocomplete="off"
              ></el-input>
            </el-form-item>
            <el-form-item label="Sex" :label-width="formLabelWidth" prop="sex">
              <el-select v-model="form.sex" placeholder="Please select">
                <el-option label="Male" value="1"></el-option>
                <el-option label="Female" value="2"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item
              label="Birthdate"
              :label-width="formLabelWidth"
              prop="birthdate"
            >
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.birthdate"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
            <el-form-item
              label="Marital Status"
              :label-width="formLabelWidth"
              prop="marital_status"
            >
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
            <el-form-item
              label="PhilHealth No."
              :label-width="formLabelWidth"
              prop="philhealth_number"
            >
              <el-input
                v-model="form.philhealth_number"
                autocomplete="off"
              ></el-input>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button
              v-if="form.formmode == 'add'"
              type="primary"
              @click="
                patientFunctions('add');
                formLoading();
              "
              >Save</el-button
            >
            <el-button
              v-if="form.formmode == 'edit'"
              type="primary"
              @click="
                patientFunctions('edit');
                formLoading();
              "
              >Save Changes</el-button
            >
          </span>
        </el-dialog>
        <!-- Add Patient form ends here-->
      </div>
    </div>
    <!-- Card ends here -->
    
    <!-- Footer -->
    <hr />
    <div class="footer">
      <div class="containter-fluid">
        <div class="row text-center">
          <span class="text-muted"
            >&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System 2020</span
          >
        </div>
      </div>
    </div>
    <!-- Footer ends -->

    <!-- Show Patient Details -->
    <el-dialog title="Patient Info" :visible.sync="dialogTableVisible">
      <el-table :data="gridData">
        <el-table-column
          property="name"
          label="Name"
          width="200"
        ></el-table-column>
        <el-table-column
          property="sex"
          label="Sex"
          width="100"
        ></el-table-column>
        <el-table-column
          property="birthdate"
          label="Birthdate"
          width="formLabelWidth"
        ></el-table-column>
        <el-table-column
          property="marital_status"
          label="Marital Status"
          width="formLabelWidth"
        ></el-table-column>
        <el-table-column
          property="philhealth_number"
          label="PhilHealth No."
          width="formLabelWidth"
        ></el-table-column>
      </el-table>
    </el-dialog>
    <!-- Show Patient Details -->
  </div>
</template>


<script>
"use strict";
import constants from "../../constants.js";
export default {
  data() {
    return {
      loading: true,
      data: [],
      patientinfo: [],
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      formLabelWidth: "120px",
      // Validation
      rules: {
        last_name: [
          { required: true, message: "Lastname is required.", trigger: "blur" },
        ],
        first_name: [
          {
            required: true,
            message: "Firstname is required.",
            trigger: "blur",
          },
        ],
        middle_name: [
          {
            required: true,
            message: "Middlename is required.",
            trigger: "blur",
          },
        ],
        sex: [
          { required: true, message: "Sex is required.", trigger: "change" },
        ],
        birthdate: [
          {
            // type: "date",
            required: true,
            message: "Please pick a date",
            trigger: "change",
          },
        ],
        marital_status: [
          {
            required: true,
            message: "Marital Status is required.",
            trigger: "change",
          },
        ],
        philhealth_number: [
          {
            required: true,
            message: "PhilHealth No. is required.",
            trigger: "blur",
          },
        ],
      },
      // Searchbox Filters
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
          width: "250px",
        },
        {
          prop: "sex",
          label: "Sex",
          width: "150px",
        },
        {
          prop: "birthdate",
          label: "Birthdate",
          width: "150px",
        },
        {
          prop: "marital_status",
          label: "Marital Status",
          width: "250px",
        },
        {
          prop: "philhealth_number",
          label: "PhilHealth #",
        },
      ],

      // Add form
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
        edit_object_index: "",
      },
      // Edit form check
      form_check: {
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

      // Show details data
      gridData: [
        {
          name: "",
          sex: "",
          birthdate: "",
          marital_status: "",
          philhealth_number: "",
        },
      ],

      //Action column
      actionCol: {
        label: "Actions",
        props: {
          align: "center",
        },

        //Action buttons
        buttons: [
          {
            props: {
              id: "info",
              type: "info",
              icon: "el-icon-info",
              circle: true,
              size: "mini",
              tooltip: "Info",
            },
            handler: (row) => {
              this.dialogTableVisible = true;
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
              this.clearFields();
              this.form.id = row.id;
              this.form.formmode = "edit";
              this.dialogFormVisible = true;

              this.form.last_name = row.last_name;
              this.form.first_name = row.first_name;
              this.form.middle_name = row.middle_name;
              this.form.name_suffix = row.name_suffix;
              this.form.sex = row.sex;
              this.form.birthdate = row.birthdate;
              this.form.marital_status = row.marital_status;
              this.form.philhealth_number = row.philhealth_number;

              this.form.edit_object_index = this.data.indexOf(row);

              (this.form_check.last_name = row.last_name),
                (this.form_check.first_name = row.first_name),
                (this.form_check.middle_name = row.middle_name),
                (this.form_check.name_suffix = row.name_suffix),
                (this.form_check.sex = row.sex),
                (this.form_check.birthdate = row.birthdate),
                (this.form_check.marital_status = row.marital_status),
                (this.form_check.philhealth_number = row.philhealth_number),
                (this.form_check.name =
                  this.form_check.last_name +
                  ", " +
                  this.form_check.name_suffix +
                  " " +
                  this.form_check.first_name +
                  " " +
                  this.form_check.middle_name.slice(0, 1) +
                  ". ");
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

              this.deletePatient(row.id, (res_value) => {
                if (res_value) {
                  data.splice(data.indexOf(row), 1);
                }
              });
            },
          },
        ],
      },
    };
  },
  methods: {
    formLoading: function () {
      const loading = this.$loading({
        lock: true,
        spinner: "el-icon-loading",
        target: "div.el-dialog",
      });
      loading.close();
    },
    getPatients: function () {
      axios
        .get("patients_get")
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPatientData(element);
          });
          this.data = response.data;
          this.loading = false;
        })
        .catch(function (error) {});
    },
    patientFunctions: function (mode) {
      switch (mode) {
        case "add":
          if (
            this.form.last_name == "" ||
            this.form.first_name == "" ||
            this.form.middle_name == "" ||
            this.form.sex == "" ||
            this.form.birthdate == "" ||
            this.form.marital_status == "" ||
            this.form.philhealth_number == ""
          ) {
            this.open_notif(
              "info",
              "Message",
              "Required fields were missing values."
            );
          } else {
            axios
              .post("add_patient", this.form)
              .then((response) => {
                if (response.status > 199 && response.status < 203) {
                  response.data.name =
                    this.form.last_name +
                    ", " +
                    this.form.name_suffix +
                    " " +
                    this.form.first_name +
                    " " +
                    this.form.middle_name.slice(0, 1) +
                    ". ";
                  response.data.sex = constants.sex[Number(this.form.sex - 1)];
                  response.data.marital_status =
                    constants.marital_status[Number(this.form.marital_status)];

                  this.data.push(response.data);
                  this.dialogFormVisible = false;
                  this.open_notif(
                    "success",
                    "Success",
                    "Patient added successfully"
                  );
                } else {
                  this.open_notif("error", "System", "Failed to add patient");
                }
              })
              .catch(function (error) {});
          }
          break;
        case "edit":
          if (
            this.form.last_name == this.form_check.last_name &&
            this.form.first_name == this.form_check.first_name &&
            this.form.middle_name == this.form_check.middle_name &&
            this.form.name_suffix == this.form_check.name_suffix &&
            this.form.sex == this.form_check.sex &&
            this.form.birthdate == this.form_check.birthdate &&
            this.form.marital_status == this.form_check.marital_status &&
            this.form.philhealth_number == this.form_check.philhealth_number
          ) {
            this.open_notif("info", "Message", "No Changes");
          } else {
            if (this.form.sex == "Male") {
              this.form.sex = 1;
            } else if (this.form.sex == "Female") {
              this.form.sex = 2;
            }
            if (this.form.marital_status == "Single") {
              this.form.marital_status = 0;
            } else if (this.form.marital_status == "Married") {
              this.form.marital_status = 1;
            } else if (this.form.marital_status == "Divorced") {
              this.form.marital_status = 2;
            } else if (this.form.marital_status == "Widowed") {
              this.form.marital_status = 3;
            } else if (this.form.marital_status == "Others/Prefer Not to Say") {
              this.form.marital_status = 4;
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
            axios
              .post("patient_edit/" + this.form.id, this.form)
              .then((response) => {
                if (response.status > 199 && response.status < 203) {
                  this.open_notif(
                    "success",
                    "Success",
                    "Changes has been saved"
                  );
                  this.dialogFormVisible = false;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].last_name = this.form.last_name;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].first_name = this.form.first_name;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].middle_name = this.form.middle_name;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].name_suffix = this.form.name_suffix;
                  this.data[parseInt(this.form.edit_object_index)].sex =
                    constants.sex[Number(this.form.sex) - 1];
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].birthdate = this.form.birthdate;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].marital_status =
                    constants.marital_status[Number(this.form.marital_status)];
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].philhealth_number = this.form.philhealth_number;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].name = this.form.name;
                }
              })
              .catch(function (error) {});
          }
          break;
      }
    },
    deletePatient: function (id, res) {
      this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        type: "warning",
      })
        .then(() => {
          var _this = this;
          axios.post("patient_delete/" + id).then(function (response) {
            if (response.status > 199 && response.status < 203) {
              _this.open_notif("success", "Success", "Deleted Successfully");
              res(id);
            }
          });
        })
        .catch((action) => {
          this.open_notif("info", "Cancelled", "No Changes");
        });
    },
    formDialog: function (id) {
      if (id == "insert_data") {
        this.form.formmode = "insert_data";
        this.resetForm();
        this.dialogFormVisible = true;
      } else if (id == "edit_data") {
        this.dialogFormVisible = true;
      }
    },
    open_notif: function (status, title, message) {
      if (status == "success") {
        this.$notify.success({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "error") {
        this.$notify.error({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "info") {
        this.$notify.info({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "warning") {
        this.$notify.warning({
          title: title,
          message: message,
          offset: 0,
        });
      }
    },
    clearFields: function () {
      this.form.last_name = "";
      this.form.first_name = "";
      this.form.middle_name = "";
      this.form.name_suffix = "";
      this.form.sex = "";
      this.form.birthdate = "";
      this.form.marital_status = "";
      this.form.philhealth_number = "";
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
  },
  mounted() {
    this.getPatients();
  },
};
</script>

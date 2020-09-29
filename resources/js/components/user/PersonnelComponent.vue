<template>
  <div>
    <!-- Header -->
    <div class="row">
      <div class="col-sm-12">
        <h2>Staffs List</h2>
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
        <el-button type="primary" @click="dialogFormVisible = true"
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
        <!-- Data table ends -->

        <!-- Add Personnel form -->
        <el-dialog
          title="Add Staff"
          :visible.sync="dialogFormVisible"
          top="0vh"
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
            <el-form-item label="Suffix" :label-width="formLabelWidth">
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
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button
              @click="
                dialogFormVisible = false;
                resetForm('form');
              "
              >Cancel</el-button
            >
            <el-button type="primary" @click="addPersonnel('form')"
              >Confirm</el-button
            >
          </span>
        </el-dialog>
        <!-- Add Personnel form ends here-->

        <!-- Show Personnel Details -->
        <el-dialog title="Staffs Details" :visible.sync="dialogTableVisible">
          <el-table :data="gridData">
            <el-table-column
              property="name"
              label="Name"
              width="200"
            ></el-table-column>
            <el-table-column
              property="sex"
              label="Sex"
              width="300"
            ></el-table-column>
            <el-table-column
              property="birthdate"
              label="Birthdate"
              width="100"
            ></el-table-column>
          </el-table>
        </el-dialog>
        <!-- Show Personnel Details -->

      </div>
    </div>
    <!-- Card ends here -->
  </div>
</template>

<script>
"use strict";
export default {
  data(){
    return{
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
      },
      data: [],
      personnelinfo: [],
      filters: [
        {
          prop: ["first_name", "last_name", "middle_name"],
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
      ],
      gridData: [
        {
          name: "",
          sex: "",
          birthdate: "",
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
              this.dialogTableVisible = true;
              this.gridData[0].name = this.buildName(
                row.first_name,
                row.middle_name,
                row.last_name,
                row.name_suffix
              );
              this.gridData[0].sex = row.sex;
              this.gridData[0].birthdate = row.birthdate;
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

              this.deletePersonnel(row.id, (res_value) => {
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
        name: "",
      },
      formLabelWidth: "120px",
    };
  },
  methods: {
    addPersonnel: async function () {
      axios
        .post("add_personnel", this.form)
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
    resetForm(addPersonnelForm) {
      this.$refs[addPersonnelForm].resetFields();
    },
    editPatient: function () {
      axios
        .post("edit_patient/" + this.form.id)
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPatientData(element);
          });
          this.patientinfo = response.data;
          console.log(response.data);
        })
        .catch(function (error) {});
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
    buildPersonnelData: function (element) {
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
    },
    getPersonnel: function () {
      axios
        .get("personnel_get")
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPersonnelData(element);
          });
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    deletePersonnel: function (id, res) {
      this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        type: "warning",
      })
        .then(() => {
          var _this = this;
          axios.post("personnel_delete/" + id).then(function (response) {
            if (response.status > 199 && response.status < 203) {
              _this.$message({
                type: "success",
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
    this.getPersonnel();
  },
};
</script>
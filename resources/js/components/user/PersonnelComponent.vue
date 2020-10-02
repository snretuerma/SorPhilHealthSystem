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
        <el-button type="primary" @click="formDialog('insert_data')"
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
          v-loading="loading"
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
          title="Staff Details"
          :visible.sync="dialogFormVisible"
          :before-close="handleClose"
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
                clearFields()
              "
              >Cancel</el-button
            >
            <el-button
              v-if="this.form.formmode == 'insert_data'"
              type="primary"
              @click="addPersonnel();formLoading()"
              >Save</el-button
            >
            <el-button
              v-if="this.form.formmode == 'edit_data'"
              type="primary"
              @click="editPersonnel();formLoading()"
              >Save Changes</el-button
            >
          </span>
        </el-dialog>
        <!-- Add Personnel form ends here-->
      </div>
    </div>
    <!-- Card ends here -->

    <!-- Show Personnel Details -->
    <el-dialog title="Staffs Info" :visible.sync="dialogTableVisible">
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
</template>

<script>
"use strict";
export default {
  data() {
    return {
      loading:true,
      data: [],
      personnelinfo: [],
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
      },
      // Searchbox Filter
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

      // Add form
      form: {
        id: "",
        last_name: "",
        first_name: "",
        middle_name: "",
        name_suffix: "",
        sex: "",
        birthdate: "",
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
        name: "",
      },

      // View info data
      gridData: [
        {
          name: "",
          sex: "",
          birthdate: "",
        },
      ],

      //Actiom Column
      actionCol: {
        label: "Actions",
        props: {
          align: "center",
        },

        //Action Buttons
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
              this.clearFields();
              this.form.id = row.id;
              this.form.formmode = "edit_data";

              this.form.last_name = row.last_name;
              this.form.first_name = row.first_name;
              this.form.middle_name = row.middle_name;
              this.form.name_suffix = row.name_suffix;
              this.form.sex = row.sex;
              this.form.birthdate = row.birthdate;

              this.form.edit_object_index = this.data.indexOf(row);

              (this.form_check.last_name = row.last_name),
                (this.form_check.first_name = row.first_name),
                (this.form_check.middle_name = row.middle_name),
                (this.form_check.name_suffix = row.name_suffix),
                (this.form_check.sex = row.sex),
                (this.form_check.birthdate = row.birthdate),
                (this.form_check.name =
                  this.form_check.last_name +
                  ", " +
                  this.form_check.name_suffix +
                  " " +
                  this.form_check.first_name +
                  " " +
                  this.form_check.middle_name.slice(0, 1) +
                  ". ");
              this.formDialog("edit_data");
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
    };
  },
  methods: {
    formLoading:function() {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          target:'div.el-dialog'
        });
          loading.close();
       
      },
    getPersonnel: function () {
      axios
        .get("personnel_get")
        .then((response) => {
          response.data.forEach((element) => {
            this.buildPersonnelData(element);
          });
          this.data = response.data;
          this.loading = false;
        })
        .catch(function (error) {});
    },
    addPersonnel: function () {
      if (
            this.form.last_name == "" ||
            this.form.first_name == "" ||
            this.form.middle_name == "" ||
            this.form.name_suffix == "" ||
            this.form.sex == "" ||
            this.form.birthdate == ""
          ) {
            this.open_notif("info", "Message", "Required fields were missing values.");
          } else {
      axios
        .post("add_personnel", this.form)
        .then((response) => {
          if (response.data.sex == 1) {
            response.data.sex = "Male";
          } else if (response.data.sex == 2) {
            response.data.sex = "Female";
          } else if (response.data.sex == 3) {
            response.data.sex = "Not Applicable";
          } else {
            response.data.sex = "Not Known";
          }

          response.data.name =
            response.data.last_name +
            ", " +
            response.data.name_suffix +
            " " +
            response.data.first_name +
            " " +
            response.data.middle_name.slice(0, 1) +
            ". ";
          this.open_notif("success", "Success", "Staff added successfully");
          this.data.push(response.data);
          
        
        })
        .catch(function (error) {})
        .finally(() => {
          var _this=this;
          
          _this.dialogFormVisible = false;
        });
          }
    },
    editPersonnel: function () {
      var _this = this;
      if (
        this.form.last_name == this.form_check.last_name &&
        this.form.first_name == this.form_check.first_name &&
        this.form.middle_name == this.form_check.middle_name &&
        this.form.name_suffix == this.form_check.name_suffix &&
        this.form.sex == this.form_check.sex &&
        this.form.birthdate == this.form_check.birthdate
      ) {
        _this.open_notif("info", "Message", "No Changes");
      } else {
        if (this.form.sex == "Male") {
          this.form.sex = 1;
        } else if (this.form.sex == "Female") {
          this.form.sex = 2;
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
          .post("edit_personnel/" + this.form.id, this.form)
          .then((response) => {
            if (response.status > 199 && response.status < 203) {
              _this.open_notif("success", "Success", "Changes has been saved");
              this.dialogFormVisible = false;
              _this.data[parseInt(_this.form.edit_object_index)].last_name =
                _this.form.last_name;
              _this.data[parseInt(_this.form.edit_object_index)].first_name =
                _this.form.first_name;
              _this.data[parseInt(_this.form.edit_object_index)].middle_name =
                _this.form.middle_name;
              _this.data[parseInt(_this.form.edit_object_index)].name_suffix =
                _this.form.name_suffix;
              _this.data[parseInt(_this.form.edit_object_index)].sex =
                _this.form.sex;
              _this.data[parseInt(_this.form.edit_object_index)].birthdate =
                _this.form.birthdate;
              _this.data[parseInt(_this.form.edit_object_index)].name =
                _this.form.name;
            }
          })
          .catch(function (error) {});
      }
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
        this.clearFields();
        this.dialogFormVisible = true;
      } else if (id == "edit_data") {
        this.dialogFormVisible = true;
      }
    },
    handleClose(done) {
      this.$confirm("Are you sure to close this?")
        .then((_) => {
          done();
        })
        .catch((_) => {});
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
  },
  mounted() {
    this.getPersonnel();
  },
};
</script>
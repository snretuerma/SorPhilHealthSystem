<template>
  <div>
    <data-tables
      :data="data"
      :page-size="10"
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
    <el-dialog title="Patient Details" :visible.sync="dialogFormVisible">
      <!-- <el-form :model="form">
                <el-form-item label="Firstname" :label-width="formLabelWidth">
                <el-input v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="Lastname" :label-width="formLabelWidth">
                <el-select v-model="form.region" placeholder="Please select a zone">
                    <el-option label="Zone No.1" value="shanghai"></el-option>
                    <el-option label="Zone No.2" value="beijing"></el-option>
                </el-select>
                </el-form-item>
            </el-form> -->
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

      <!-- <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="primary" @click="dialogFormVisible = false"
          >Confirm</el-button
        >
      </span> -->
    </el-dialog>
    <!-- <div id="sample" class="modal" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Patient Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <el-table :data="gridData">
              <el-table-column property="name" label="Name" width="formLabelWidth"></el-table-column>
              <el-table-column property="sex" label="Sex" width="formLabelWidth"></el-table-column>
              <el-table-column property="birthdate" label="Birthdate" width="formLabelWidth"></el-table-column>
              <el-table-column property="marital_status" label="Marital Status" width="formLabelWidth"></el-table-column>
              <el-table-column property="philhealth_number" label="PhilHealth No." width="formLabelWidth"></el-table-column>
            </el-table>
                </div>
                
                </div>
            </div>
        </div> -->
  </div>
</template>

<script>
"use strict";
export default {
  data() {
    return {
      data: [],
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
            },
            handler: (row) => {
              // $('#sample').modal();
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
            },
            handler: (row) => {
              this.data.splice(this.data.indexOf(row), 1);
            },
          },
        ],
      },
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        name: "",
        region: "",
        date1: "",
        date2: "",
        delivery: false,
        type: [],
        resource: "",
        desc: "",
      },
      formLabelWidth: "120px",
    };
  },
  methods: {
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
  },
  mounted() {
    this.getPatients();
  },
};
</script>

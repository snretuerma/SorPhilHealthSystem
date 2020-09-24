<template>
  <div>
    <div style="margin-bottom: 10px">
      <el-row>
        <el-col :span="10">
          <el-input v-model="filters[0].value" placeholder="Search"></el-input>
        </el-col>
      </el-row>
    </div>
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
      filters: [
        {
          prop: ['last_name', 'first_name', 'philhealth_number'],
          value: ''
        }
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
              this.$message("View clicked");
              row.flow_no = "hello word" + Math.random();
              row.content = Math.random() > 0.5 ? "Water flood" : "Lock broken";
              row.flow_type = Math.random() > 0.5 ? "Repair" : "Help";
            },
          },
          {
            props: {
              type: "primary",
              icon: "el-icon-edit",
              circle: true,
            },
            handler: (row) => {
              this.$message("Edit clicked");
              row.flow_no = "hello word" + Math.random();
              row.content = Math.random() > 0.5 ? "Water flood" : "Lock broken";
              row.flow_type = Math.random() > 0.5 ? "Repair" : "Help";
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

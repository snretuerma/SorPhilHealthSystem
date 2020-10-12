<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Restore List</h2>
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
            :width="title.width"
            :key="title.label"
            sortable="custom"
          >
          
          </el-table-column>
          <p slot="append"></p>
        </data-tables>
        <el-dialog
          title="Budget Details"
          :visible.sync="dialogFormVisible"
          top="0vh"
        >
          <el-form :model="form" :rules="rules" ref="form">
            <el-form-item
              label="Start date"
              :label-width="formLabelWidth"
              prop="start_date"
            >
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.start_date"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
            <el-form-item
              label="Amount"
              :label-width="formLabelWidth"
              prop="total"
            >
              <el-input v-model="form.total" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item
              label="End date"
              :label-width="formLabelWidth"
              prop="end_date"
            >
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.end_date"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="Hospital code" :label-width="formLabelWidth">
              <!-- <el-select v-model="form.codeholder" @change="selected">
                <el-option v-for="option in options" :value="option">
                  {{ option }}
                </el-option>
              </el-select> -->
              <!-- <el-select v-model="value" placeholder="Select">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select> -->
              <el-select
                v-model="form.hospital_code"
                @change="onChange(form.hospital_code)"
                placeholder="Please select"
              >
                <el-option label="DFBDSMH" value="1"></el-option>
                <el-option label="DDH" value="2"></el-option>
                <el-option label="IDH" value="3"></el-option>
                <el-option label="SREDH" value="4"></el-option>
                <el-option label="VLPMDH" value="5"></el-option>
                <el-option label="MagMCH" value="6"></el-option>
                <el-option label="MatMCH" value="7"></el-option>
                <el-option label="PGGMH" value="8"></el-option>
                <el-option label="PDMH" value="9"></el-option>
              </el-select>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button
              v-if="form.formmode == 'add'"
              type="primary"
              @click="addBudget('add')"
              >Save</el-button
            >

            <el-button
              v-if="form.formmode == 'edit'"
              type="primary"
              @click="addBudget('edit')"
              >Save changes</el-button
            >
          </span>
        </el-dialog>
      </div>
      <!-- Show Patient Details -->
      <el-dialog title="Personnel Details" :visible.sync="dialogTableVisible">
        <el-table :data="gridData">
          <el-table-column
            property="psfname"
            label="Firstname"
            width="200"
          ></el-table-column>
          <el-table-column
            property="psmname"
            label="Middlename"
            width="200"
          ></el-table-column>
          <el-table-column
            property="pslname"
            label="Lastname"
            width="formLabelWidth"
          ></el-table-column>
        </el-table>
      </el-dialog>
      <!-- Show Patient Details -->
    </div>
  </div>
</template>


<script>
"use strict";
export default {
  data() {
    return {
      data: [],
      budgetInfo: [],
      rules: {
        start_date: [
          {
            required: true,
            message: "Start date is required.",
            trigger: "blur",
          },
        ],
        total: [
          { required: true, message: "Amount is required.", trigger: "blur" },
        ],
        end_date: [
          { required: true, message: "End date is required.", trigger: "blur" },
        ],
        hospital_code: [
          {
            required: true,
            message: "Hospital code is required.",
            trigger: "blur",
          },
        ],
      },
      filters: [
        {
          prop: ["philhealth", "pfname", "admission_date", "discharge_date"],
          value: "",
        },
      ],
      titles: [
        {
          prop: "philhealth",
          label: "Philhealth No.",
          width:"150px"
        },
        {
          prop: "pfname",
          label: "Patient",
        },
        {
          prop: "admission_date",
          label: "Admit",
        },
        {
          prop: "discharge_date",
          label: "Discharge",
        },
      ],
      gridData: [
        {
          psfname: "",
          psmname: "",
          pslname: "",
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
              this.gridData[0].psfname = row.psfname;
              this.gridData[0].psmname = row.psmname;
              this.gridData[0].pslname = row.pslname;
              console.log(row);
              
            },
          },
          {
            props: {
              type: "success",
              icon:"el-icon-check",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
             this.editRestore(row.id);
            },
          },
        ],
      },
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        start_date: "",
        total: "",
        end_date: "",
        formmode: "",
        hospital_code: "",
        codeholder: "",
      },
      formLabelWidth: "120px",
    };
  },
  methods: {
    onChange: function (event) {
      this.form.codeholder = event;
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
    editRestore: function (id) {
      this.$confirm("Are you sure you want to restore?", "Confirm Restore", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Restore",
        cancelButtonText: "Cancel",
        type: "primary",
      })
        .then(() => {
          var _this = this;
          axios.post("edit_restore/" + id).then(function (response) {
              console.log(response.data);
            if (response.status > 199 && response.status < 203) {
             _this.open_notif('success','Restore','Successfully');
             _this.getRestore();
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
    getRestore: function () {
      axios
        .get("restore_get")
        .then((response) => {
          response.data.forEach((entry) => {
            if (entry.pnamesuffix == null) {
              entry.pfname =
                entry.plname +
                ", " +
                entry.pfname +
                " " +
                entry.pmname.slice(0, 1) +
                ".";
            } else {
              entry.pfname =
                entry.plname +
                " " +
                entry.pnamesuffix +
                ", " +
                entry.pfname +
                " " +
                entry.pmname.slice(0, 1) +
                ".";
            }
          });
          this.data = response.data;
        })
        .catch(function (error) {});
    },
  },
  mounted() {
    this.getRestore();
  },
};
</script>

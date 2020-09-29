<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Budget List</h2>
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
        <el-button
          type="primary"
          @click="
            dialogFormVisible = true;
            form.formmode = 'add';
            clearfield();
          "
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
              <el-select v-model='form.hospital_code'  @change="onChange(form.hospital_code)" placeholder="Please select">
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
      <el-dialog title="Budget Details" :visible.sync="dialogTableVisible">
        <el-table :data="gridData">
          <el-table-column
            property="start_date"
            label="Start date"
            width="200"
          ></el-table-column>
          <el-table-column
            property="total"
            label="Amount"
            width="200"
          ></el-table-column>
          <el-table-column
            property="end_date"
            label="End date"
            width="formLabelWidth"
          ></el-table-column>
          <el-table-column
            property="hospital_code"
            label="Hospital"
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
          prop: ["start_date", "total", "end_date", "hospital_code"],
          value: "",
        },
      ],
      titles: [
        {
          prop: "start_date",
          label: "Start date",
        },
        {
          prop: "total",
          label: "Amount",
        },
        {
          prop: "end_date",
          label: "End date",
        },
        {
          prop: "hospital_code",
          label: "Hospital",
        },
      ],
      gridData: [
        {
          start_date: "",
          total: "",
          end_date: "",
          hospital_code: "",
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
              this.gridData[0].start_date = row.start_date;
              this.gridData[0].total = row.total;
              this.gridData[0].end_date = row.end_date;
              this.gridData[0].hospital_code = row.hospital_code;
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
              this.clearfield();
              this.form.id = row.id;
              this.form.formmode = "edit";
              this.dialogFormVisible = true;
              this.form.start_date = row.start_date;
              this.form.total = row.total;
              this.form.end_date = row.end_date;
              if (row.hospital_code == "DFBDSMH") {
                this.form.codeholder = 1;
              } else if (row.hospital_code == "DDH") {
                this.form.codeholder = 2;
              } else if (row.hospital_code == "IDH") {
                this.form.codeholder = 3;
              } else if (row.hospital_code == "SREDH") {
                this.form.codeholder = 4;
              } else if (row.hospital_code == "VLPMDH") {
                this.form.codeholder = 5;
              } else if (row.hospital_code == "MagMCH") {
                this.form.codeholder = 6;
              } else if (row.hospital_code == "MatMCH") {
                this.form.codeholder = 7;
              } else if (row.hospital_code == "PGGMH") {
                this.form.codeholder = 8;
              } else if (row.hospital_code == "PDMH") {
                this.form.codeholder = 9;
              }
              this.form.hospital_code = row.hospital_code;
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
      this.form.codeholder=event;
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
          axios.post("delete_budget/" + id).then(function (response) {
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
    getBudget: function () {
      axios
        .get("adminbudget_get")
        .then((response) => {
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    clearfield: function () {
      this.form.start_date = "";
      this.form.total = "";
      this.form.end_date = "";
    },
    addBudget: function (mode) {
      switch (mode) {
        case "add":
          // alert('add');
          this.axios
            .post("adminadd_budget", this.form)
            .then(this.getBudget(), (this.dialogFormVisible = false))
            .catch(function (error) {});
          break;
        case "edit":
          // alert('edit');
          // if (this.form.hospital_code == "DFBDSMH") {
          //   this.form.codeholder = 1;
          // } else if (this.form.hospital_code == "DDH") {
          //   this.form.codeholder = 2;
          // } else if (this.form.hospital_code == "IDH") {
          //   this.form.codeholder = 3;
          // } else if (this.form.hospital_code == "SREDH") {
          //   this.form.codeholder = 4;
          // } else if (this.form.hospital_code == "VLPMDH") {
          //   this.form.codeholder = 5;
          // } else if (this.form.hospital_code == "MagMCH") {
          //   this.form.codeholder = 6;
          // } else if (this.form.hospital_code == "MatMCH") {
          //   this.form.codeholder = 7;
          // } else if (this.form.hospital_code == "PGGMH") {
          //   this.form.codeholder = 8;
          // } else if (this.form.hospital_code == "PDMH") {
          //   this.form.codeholder = 9;
          // }
          // this.form.codeholder=this.codeholder;
          // this.form.hospital_code=this.options.indexOf(this.age)
          axios
            .post("adminedit_budget/" + this.form.id, this.form)
            .then(this.getBudget(), (this.dialogFormVisible = false))
            .catch(function (error) {});
          break;
      }
    },
  },
  mounted() {
    this.getBudget();
  },
};
</script>

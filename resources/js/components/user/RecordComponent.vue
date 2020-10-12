<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Record List</h2>
      </div>
    </div>
    <hr />
    <div class="row">
      <!-- <div class="col-sm-10" align="left">
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
      </div> -->
      <!-- <div class="col-sm-2" align="right">
        <el-button
          type="primary"
          @click="
            dialogFormVisible = true;
            form.formmode = 'add';
            clearfield();
          "
          >Add</el-button
        >
      </div> -->
    </div>
    <div class="card">
      <div class="card-body">
        <el-table
          :data="
            pagedTableData.filter(
              (data) =>
                !search ||
                data.first_name.toLowerCase().includes(search.toLowerCase()) ||
                data.philhealth_number
                  .toLowerCase()
                  .includes(search.toLowerCase())
            )
          "
        >
          <el-table-column
            width="115"
            label="Philhealth"
            prop="philhealth_number"
          >
          </el-table-column>
          <el-table-column width="177" label="Patient" prop="first_name">
          </el-table-column>
          <el-table-column width="100" label="Admit" prop="admission_date">
          </el-table-column>
          <el-table-column width="110" label="Discharge" prop="discharge_date">
          </el-table-column>
          <el-table-column width="125" label="Diagnosis" prop="final_diagnosis">
          </el-table-column>
          <el-table-column width="115" label="Record type" prop="record_type">
          </el-table-column>
          <el-table-column width="115" label="Total fee" prop="total_fee">
          </el-table-column>
          <el-table-column width="130" align="right" fixed="right">
            <template slot="header" slot-scope="scope">
              <el-input
                v-model="search"
                size="mini"
                placeholder="Type to search"
              />
            </template>
            <template slot-scope="scope">
              <el-tooltip
                class="item"
                effect="light"
                content="view"
                placement="top"
              >
                <el-button
                  size="mini"
                  type="info"
                  icon="el-icon-info"
                  circle
                  @click="handleView(scope.$index, scope.row)"
                >
                </el-button>
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="light"
                content="delete"
                placement="top"
              >
                <el-button
                  size="mini"
                  type="danger"
                  icon="el-icon-delete"
                  circle
                  @click="handleDelete(scope.$index, scope.row)"
                ></el-button>
              </el-tooltip>
            </template>
          </el-table-column>
        </el-table>
        <div style="text-align: center">
          <el-pagination
            layout="prev, pager, next"
            :total="this.data.length"
            @current-change="setPage"
          >
          </el-pagination>
        </div>
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
      <el-dialog
        title="Personnel Details"
        :visible.sync="dialogTableVisible"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
      >
        <el-table :data="staff">
          <el-table-column
            property="first_name"
            label="Firstname"
            width="200"
          ></el-table-column>
          <el-table-column
            property="middle_name"
            label="Middlename"
            width="200"
          ></el-table-column>
          <el-table-column
            property="last_name"
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
      page: 1,
      pageSize: 10,
      search: "",
      data: [],
      staff: [],
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
          width: "150px",
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
  computed: {
    pagedTableData() {
      return this.data.slice(
        this.pageSize * this.page - this.pageSize,
        this.pageSize * this.page
      );
    },
  },
  methods: {
    masknumber: function (num) {
      num = parseFloat(num)
        .toFixed(2)
        .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
      return num;
    },
    setPage(val) {
      this.page = val;
    },
    handleDelete(index, row) {
      var data = this.data;

              this.deleteRecord(row.id, (res_value) => {
                if (res_value) {
                  data.splice(data.indexOf(row), 1);
                }
              });
    },
    handleView(index, row) {
      console.log(row);
      axios
        .post("personnel_get/" + row.id)
        .then((response) => {
          this.staff = response.data.personnels;
        })
        .catch(function (error) {});
      this.dialogTableVisible = true;
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
    deleteRecord: function (id) {
      this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        type: "warning",
      })
        .then(() => {
          var _this = this;
          axios.post("delete_record/" + id).then(function (response) {
            if (response.status > 199 && response.status < 203) {
             if (response.status > 199 && response.status < 203) {
               _this.open_notif("success", "Success",  "Succesfully! Deleted");
            
            }
              _this.getRecord();
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
    getRecord: function () {
      axios
        .get("record_get")
        .then((response) => {
          console.log(response.data);
          response.data.forEach((entry) => {
            if (entry.name_suffix == null) {
              entry.first_name =
                entry.first_name +
                " " +
                entry.middle_name.slice(0, 1) +
                ". " +
                entry.last_name;
            } else {
              entry.first_name =
                entry.first_name +
                " " +
                entry.middle_name.slice(0, 1) +
                ". " +
                entry.last_name +
                " " +
                entry.name_suffix +
                ", ";
            }
            // entry.patient.hospital_id =
            //   constants.hospital_code[Number(entry.patient.hospital_id) - 1];
            entry.total_fee = this.masknumber(entry.total_fee);
          });
          this.data = response.data;
          //   console.log(response.data);
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
          axios
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
    this.getRecord();
  },
};
</script>

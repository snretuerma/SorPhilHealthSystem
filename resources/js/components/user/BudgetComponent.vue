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
        <el-button type="primary" @click="dialogFormVisible = true;form.formmode='add';clearfield();"
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
             <el-form-item label="Start date" :label-width="formLabelWidth"  prop="start_date">
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.start_date"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="Amount" :label-width="formLabelWidth" prop="total">
              <el-input v-model="form.total" autocomplete="off" ></el-input>
            </el-form-item>
            <el-form-item label="End date" :label-width="formLabelWidth"  prop="end_date">
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="form.end_date"
                style="width: 100%"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button v-if="form.formmode=='add'" type="primary" @click="addBudget('add')">Save</el-button>
            
            <el-button v-if="form.formmode=='edit'"  type="primary" @click="addBudget('edit')">Save changes</el-button>
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
          { required: true, message: "Start date is required.", trigger: "blur" },
        ],
        total: [
          { required: true, message: "Amount is required.", trigger: "blur" },
        ],
        end_date: [
          { required: true, message: "End date is required.", trigger: "blur" },
        ],
      },
      filters: [
        {
          prop: ["start_date", "total", "end_date"],
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
      ],
      gridData: [
        {
          start_date: "",
          total: "",
          end_date: "",
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
              this.form.formmode='edit';
              this.dialogFormVisible = true;
              this.form.start_date=row.start_date;
              this.form.total=row.total;
              this.form.end_date=row.end_date;
              this.form.edit_object_index = this.data.indexOf(row);
              this.form_check.start_date = row.start_date;
              this.form_check.total = row.total;
              this.form_check.end_date = row.end_date;
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
        start_date: "",
        total: "",
        end_date: "",
        formmode:"",
        edit_object_index:""
      },
      form_check: {
        start_date: "",
        total: "",
        end_date: ""
      },
      formLabelWidth: "120px",
    };
  },
  methods: {
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
        .get("budget_get")
        .then((response) => {
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    clearfield:function(){
      this.form.start_date="";
      this.form.total="";
      this.form.end_date="";
    }
    ,
    addBudget:  function (mode) {
      switch (mode) {
        case 'add':
          // alert('add');
          if (
            this.form.start_date == "" ||
            this.form.end_date == "" ||
            this.form.total == ""
          ) {
           this.open_notif("info", "Message", "All field required!");  
          }else{
             axios
              .post("add_budget", this.form)
              .then((response) => {
                // this.getBudget();
                this.data.push(response.data);
                this.dialogFormVisible = false;
                if (response.status > 199 && response.status < 203) {
                  this.open_notif("success", "Message", "Successfully added!");
                } else {
                  this.open_notif("error", "Message", "Record failed to add!");
                }
              })
              .catch(function (error) {});
          }
          break;
        case 'edit':
          // alert('edit');
          if(this.form.start_date==this.form_check.start_date
          && this.form.end_date==this.form_check.end_date
          && this.form.total==this.form_check.total){
            this.open_notif('info','Message','No changes');
          }else{
            axios
            .post("edit_budget/" + this.form.id, this.form)
            .then((response)=>{
              // this.getBudget();
              if(response.status >199 && response.status <203){
                  this.data[parseInt(this.form.edit_object_index)].start_date = this.form.start_date;
                    this.data[parseInt(this.form.edit_object_index)].total= this.form.total;
                    this.data[parseInt(this.form.edit_object_index)].end_date = this.form.end_date;
              this.dialogFormVisible = false;
              this.open_notif('success','Message','Successfully change!');
              }
             
            })
            .catch(function (error) {});
          
          }
          break;
      }
    },
  },
  mounted() {
    this.getBudget();
  },
};
</script>

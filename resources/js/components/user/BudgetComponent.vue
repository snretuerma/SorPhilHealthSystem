<template>
  <div>
    <!-- Header -->
    <div class="row">
      <div class="col-sm-12">
        <h2>Budget List</h2>
      </div>
    </div>
    <hr />
    <!-- End Header -->

    <div class="row">
      <div class="col-sm-6" align="left">
        <div style="margin-bottom: 10px">
          <el-row>
            <el-col :span="10">
              <el-input
                size="medium"
                v-model="filters[0].value"
                placeholder="Search"
              ></el-input>
            </el-col>
          </el-row>
        </div>
      </div>
      <div class="col-sm-6" align="right">
        <el-button type="primary" size="medium" @click="formDialog('export_data')">Export</el-button>
        <el-button type="primary" size="medium" @click="formDialog('import_data')">Import</el-button>
        <el-button type="primary" size="medium" @click="dialogFormVisible = true;form.formmode='add';clearfield();">Add</el-button>
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
            sortable="custom"
          >
          </el-table-column>
          <p slot="append"></p>
        </data-tables>
        <!-- Data table ends -->

        <!-- Add/Edit Budget Dialog -->
        <el-dialog
          title="Budget Details"
          :visible.sync="dialogFormVisible"
          top="5vh"
          :close-on-press-escape="false"
          :close-on-click-modal="false"
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
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button
              v-if="form.formmode == 'add'"
              type="primary"
              @click="
                addBudget('add');
                openFullScreen2();
              "
              >Save</el-button
            >
            <el-button
              v-if="form.formmode == 'edit'"
              type="primary"
              @click="
                addBudget('edit');
                openFullScreen2();
              "
              >Save changes</el-button
            >
          </span>
        </el-dialog>
        <!-- Add/Edit Dialog -->
      </div>
      <!-- Show Patient Details -->
      <el-dialog title="Budget Info" :visible.sync="dialogTableVisible">
        <el-table :data="gridData">
          <el-table-column
            property="start_date"
            label="Start date"
            width="200"
          ></el-table-column>
          <el-table-column
            property="total"
            label="Amount"
            width="300"
          ></el-table-column>
          <el-table-column
            property="end_date"
            label="End date"
            width="200"
          ></el-table-column>
        </el-table>
      </el-dialog>
      <!-- Show Patient Details -->

      <!-- Import budget via excel file-->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Import Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" enctype="multipart/form-data" action="/budget_import">
                <input type="hidden" name="" id="">
                <div class="modal-body">
                    <div class="form-group">
                      <input type="hidden" name="i_action" id="i_action" value="BudgetImport">
                      <label>Select excel file for upload (.csv)</label><br>
                      <input type="file" @change="selectFile($event)" id="excelcontent" name="budgets" accept=".csv" class="w-100" style="border:1px solid rgba(0,0,0,0.1);border-radius:4px;"/>
                      <div v-if="progressbar_import" class="progress" style="margin-top:15px;">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                          0%
                        </div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" name="upload" class="btn btn-primary" v-on:click="progressbar_import=true; onSubmit()" v-bind:disabled="enableUpload === false">Import</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- Import excel end-->

        <!-- Export excel-->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Export Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="get" enctype="multipart/form-data" action="budget_export/">
                <input type="hidden" name="" id="">
                <input type="hidden" name="e_action" id="e_action" value="BudgetExport">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select excel file type</label><br>
                        <select name="exceltype" class="form-control">
                          <option value="csv">CSV</option>
                          <option value="xlsx">XLSX</option>
                          <option value="xls">XLS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="upload" class="btn btn-primary" >Export</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- Export excel end-->

    </div>
  </div>
</template><script>
"use strict";
export default {
  data() {
    return {
      loading: true,
      data: [],
      budgetInfo: [],
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      formLabelWidth: "120px",
      progressbar_import: false,
      enableUpload: false,
      // Validation
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
      },

      // Searchbox Filter
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

      // Add form
      form: {
        id: "",
        start_date: "",
        total: "",
        end_date: "",
        formmode: "",
        edit_object_index: "",
      },

      // Edit form check
      form_check: {
        start_date: "",
        total: "",
        end_date: "",
      },

      // View info data
      gridData: [
        {
          start_date: "",
          total: "",
          end_date: "",
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
              this.form.formmode = "edit";
              this.dialogFormVisible = true;

              this.form.start_date = row.start_date;
              this.form.total = row.total;
              this.form.end_date = row.end_date;

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
    };
  },
  methods: {
    openFullScreen2: function () {
      const loading = this.$loading({
        lock: true,
        spinner: "el-icon-loading",
        target: "div.el-dialog",
      });
      loading.close();
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
              _this.open_notif("success", "Budget", "Successfully deleted!");
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
      var _this = this;
      axios
        .get("budget_get")
        .then((response) => {
          response.data.forEach(function (entry) {
            entry.total = _this.masknumber(entry.total);
          });
          this.data = response.data;
          this.loading = false;
        })
        .catch(function (error) {});
    },
    clearfield: function () {
      this.form.start_date = "";
      this.form.total = "";
      this.form.end_date = "";
    },
    masknumber: function (num) {
      num = parseFloat(num)
        .toFixed(2)
        .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
      return num;
    },
    addBudget: function (mode) {
      switch (mode) {
        case "add":
          if (
            this.form.start_date == "" ||
            this.form.end_date == "" ||
            this.form.total == ""
          ) {
            this.open_notif("info", "Invalid", "All fields required!");
          } else {
            var _this = this;
            axios
              .post("add_budget", this.form)
              .then((response) => {
                response.data.start_date = this.form.start_date;
                response.data.end_date = this.form.end_date;
                response.data.total = this.masknumber(this.form.total);
                this.data.push(response.data);
                this.dialogFormVisible = false;
                if (response.status > 199 && response.status < 203) {
                  this.open_notif("success", "Budget", "Successfully added!");
                } else {
                  this.open_notif("error", "System", "Record failed to add!");
                }
              })
              .catch(function (error) {})
              .finally(function () {});
          }
          break;
        case "edit":
          if (
            this.form.start_date == this.form_check.start_date &&
            this.form.end_date == this.form_check.end_date &&
            this.form.total == this.form_check.total
          ) {
            this.open_notif("info", "Note : ", "No changes were made");
          } else {
            this.form.total = parseFloat(this.form.total.replace(/,/g, ""));
            axios
              .post("edit_budget/" + this.form.id, this.form)
              .then((response) => {
                if (response.status > 199 && response.status < 203) {
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].start_date = this.form.start_date;
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].total = this.masknumber(this.form.total);
                  this.data[
                    parseInt(this.form.edit_object_index)
                  ].end_date = this.form.end_date;

                  this.dialogFormVisible = false;
                  this.open_notif(
                    "success",
                    "Notice : ",
                    "Successfully changed!"
                  );
                }
              })
              .catch(function (error) {});
          }
          break;
      }
    },
    formDialog: function (id) {
      if(id == "import_data"){
        $("#importModal").modal({backdrop: 'static', keyboard: false});
      }else if(id == "export_data"){
        $("#exportModal").modal({backdrop: 'static', keyboard: false});
      }
    },
    selectFile(event){
      if(event.target.value){
        this.enableUpload = true;
      }else{
        this.enableUpload = false;
      }
    },
    onSubmit(){
      var _this = this;
      var formData = new FormData();
      formData.append("i_action", $("#i_action").val());
      formData.append("budgets[]", $("#excelcontent").get(0).files[0]);
       axios.post( 'budget_import',
          formData,
          {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: function( progressEvent ) {
              this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
            
              $('.progress-bar').css('width', this.uploadPercentage +'%').attr('aria-valuenow', this.uploadPercentage);
              $('.progress-bar').html(this.uploadPercentage + "%");

            }.bind(this)
          }
        ).then(function(res){
          setTimeout(function(){
            _this.progressbar_import = false;
            $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
            $('.progress-bar').html('0%');
            $("#importModal").modal('hide');
            $("#excelcontent").val('');
            
          },2000);
          var total_imported = res.data;

          if(total_imported == 0){
            _this.open_notif("warning", "Import", "No row to be import");
          }else if(total_imported > 0){
            _this.open_notif("success", "Import", "Successfully imported: " + res.data + " row");
            _this.getBudget();
          }
          
        })
        .catch(function(res){
           _this.progressbar_import = false;
           $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
           $('.progress-bar').html('0%');
           $("#excelcontent").val('');
           $("#importModal").modal('hide');
           _this.open_notif("error", "Message", "FAILURE!! Something went wrong!");
        });
    },
    open_notif: function (status, title, message) {
        if(status == "success"){
            this.$notify.success({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "error"){
            this.$notify.error({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "info"){
            this.$notify.info({
              title: title,
              message: message,
              offset: 0
            });
        }else if(status == "warning"){
            this.$notify.warning({
              title: title,
              message: message,
              offset: 0
            });
        }
    },
  },
  mounted() {
    this.getBudget();
  },
};
</script>

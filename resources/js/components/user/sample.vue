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
      <div class="col-sm-12" align="right" style="margin-bottom: 10px">
        <el-button
          type="primary"
          size="medium"
          @click="formDialog('export_data')"
          >Export</el-button
        >
        <el-button
          type="primary"
          size="medium"
          @click="formDialog('import_data')"
          >Import</el-button
        >
        <el-button
          type="primary"
          size="medium"
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
        <el-table
          v-loading="loading"
          :data="pagedTableData"
            
        >
          <el-table-column
            width="115"
            label="Philhealth"
            prop="philhealth_number"
          >
          </el-table-column>
          <el-table-column width="200" label="Patient" prop="name">
          </el-table-column>
          <el-table-column width="100" label="Sex" prop="sex">
          </el-table-column>
          <el-table-column width="110" label="Birthdate" prop="birthdate">
          </el-table-column>
          <el-table-column
            min-width="175"
            label="Marital"
            prop="marital_status"
          >
          </el-table-column>
          <el-table-column width="200" align="right" fixed="right">
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
                content="Add Medical Record"
                placement="top"
              >
                <el-button
                  size="mini"
                  type="success"
                  icon="el-icon-plus"
                  circle
                  @click="handleAddMedical(scope.$index, scope.row)"
                >
                </el-button>
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="light"
                content="View"
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
                content="Edit"
                placement="top"
              >
                <el-button
                  size="mini"
                  type="primary"
                  icon="el-icon-edit"
                  circle
                  @click="handleEdit(scope.$index, scope.row)"
                >
                </el-button>
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="light"
                content="Delete"
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
            layout="sizes, prev, pager, next"
            :total="this.data.length"
            :page-sizes="[10, 20, 30, 40]"
            :page-size="10"
            @current-change="setPage"

          >
          </el-pagination>
        </div>

        <!-- Import patient via excel file-->
        <div
          class="modal fade"
          id="importModal"
          tabindex="-1"
          aria-labelledby="ModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Import Patient</h5>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form
                method="post"
                enctype="multipart/form-data"
                action="/patients_import"
              >
                <input type="hidden" name="" id="" />
                <div class="modal-body">
                  <div class="form-group">
                    <label>Select excel file for upload (.csv)</label><br />
                    <input
                      type="file"
                      @change="selectFile($event)"
                      id="excelcontent"
                      name="patients"
                      accept=".csv"
                      class="w-100"
                      style="
                        border: 1px solid rgba(0, 0, 0, 0.1);
                        border-radius: 4px;
                      "
                    />
                    <div
                      v-if="progressbar_import"
                      class="progress"
                      style="margin-top: 15px"
                    >
                      <div
                        class="progress-bar progress-bar-striped active"
                        role="progressbar"
                        aria-valuenow="0"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width: 0%"
                      >
                        0%
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    name="upload"
                    class="btn btn-primary"
                    v-on:click="
                      progressbar_import = true;
                      onSubmit();
                    "
                    v-bind:disabled="enableUpload === false"
                  >
                    Import
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- Import excel end-->

        <!-- Export excel-->
        <div
          class="modal fade"
          id="exportModal"
          tabindex="-1"
          aria-labelledby="ModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Export Patient</h5>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form
                method="get"
                enctype="multipart/form-data"
                action="patients_export/"
              >
                <input type="hidden" name="" id="" />
                <div class="modal-body">
                  <div class="form-group">
                    <label>Select excel file type</label><br />
                    <select name="exceltype" class="form-control">
                      <option value="csv">CSV</option>
                      <option value="xlsx">XLSX</option>
                      <option value="xls">XLS</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                  >
                    Close
                  </button>
                  <button type="submit" name="upload" class="btn btn-primary">
                    Export
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- Export excel end-->

        <!-- Add medical form -->
		<el-dialog title="Add Medical Record" :visible.sync="dialogFormMedicalVisible" top="0vh">
			<el-form :model="formMedical" :rules="rules" ref="form">
				<el-form-item label="Admission Date" :label-width="formLabelWidth" prop="admission_date">
					<el-date-picker type="date" placeholder="Pick a date" v-model="formMedical.admission_date" style="width: 100%" value-format="yyyy-MM-dd"></el-date-picker>
				</el-form-item>
				<el-form-item label="Discharge Date" :label-width="formLabelWidth" prop="discharge_date">
					<el-date-picker type="date" placeholder="Pick a date" v-model="formMedical.discharge_date" style="width: 100%" value-format="yyyy-MM-dd"></el-date-picker>
				</el-form-item>
				<el-form-item label="Final Diagnosis" :label-width="formLabelWidth" prop="final_diagnosis">
					<el-input v-model="formMedical.final_diagnosis" autocomplete="off"></el-input>
				</el-form-item>
				<el-form-item label="Record Type" :label-width="formLabelWidth" prop="record_type">
					<el-input v-model="formMedical.record_type" autocomplete="off"></el-input>
				</el-form-item>
				<el-form-item label="Total Fee" :label-width="formLabelWidth" prop="total_fee">
					<el-input v-model="formMedical.total_fee" autocomplete="off"></el-input>
				</el-form-item>
				<el-form-item label="Personnel" :label-width="formLabelWidth" prop="total_fee">
					<el-input v-model="formMedical.personnel" autocomplete="off"></el-input>
				</el-form-item>
				<el-form-item label="Personnel Type" :label-width="formLabelWidth" prop="personnel_type">
					<el-select v-model="form.personnel_type" placeholder="Please select">
						<el-option label="Male" value="1"></el-option>
						<el-option label="Female" value="2"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="Contribution Type" :label-width="formLabelWidth" prop="contribution_type">
					<el-select v-model="form.contribution_type" placeholder="Please select">
						<el-option label="Male" value="1"></el-option>
						<el-option label="Female" value="2"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="Computed PF" :label-width="formLabelWidth" prop="computed_pf">
					<el-input v-model="form.computed_pf" autocomplete="off"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogFormMedicalVisible = false">Cancel</el-button>
				<el-button v-if="this.formMedical.formmode == 'addMedical'" type="primary" @click="addPatient()">Save</el-button>
			</span>
		</el-dialog>
		<!-- Add medical form ends here-->
    

        <!-- Add Patient form -->
        <el-dialog
          title="Patient Details"
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
              <span class="font-italic text-danger" v-if="errors.last_name"
                ><small>{{ errors.last_name[0] }}</small></span
              >
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
    </div>
    <!-- Card ends here -->
  </div>
</template>


<script>
"use strict";
export default {
  data() {
    return {
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
      errors: [],
      loading: true,
      filtered: [],
      total:0,
      page: 1,
      pageSize: 10,
      search: "",
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
     
      // Datatable Layout
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      dialogFormVisible_import_excel: false,
      progressbar_import: false,
      enableUpload: false,
      dialogFormMedicalVisible: false,
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
      formMedical: {
        admission_date: "",
        discharge_date: "",
        final_diagnosis: "",
        record_type: "",
        total_fee: "",
        formmode: "",
      },
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
      formLabelWidth: "125px",
    };
  },
  computed: {
    pagedTableData() {
      if(this.search == null) return this.data;
      
        this.filtered = this.data.filter(data => !this.search || data.first_name.toLowerCase().includes(this.search.toLowerCase()) || data.last_name.toLowerCase().includes(this.search.toLowerCase()));
        
        this.total = this.filtered.length;

        return this.filtered.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
    
    },
  },
  methods: {
    selectFile(event) {
      if (event.target.value) {
        this.enableUpload = true;
      } else {
        this.enableUpload = false;
      }
    },
    onSubmit() {
      var _this = this;
      var formData = new FormData();
      formData.append("patients[]", $("#excelcontent").get(0).files[0]);
      axios
        .post("patients_import", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: function (progressEvent) {
            this.uploadPercentage = parseInt(
              Math.round((progressEvent.loaded * 100) / progressEvent.total)
            );

            $(".progress-bar")
              .css("width", this.uploadPercentage + "%")
              .attr("aria-valuenow", this.uploadPercentage);
            $(".progress-bar").html(this.uploadPercentage + "%");
          }.bind(this),
        })
        .then(function (res) {
          setTimeout(function () {
            _this.progressbar_import = false;
            $(".progress-bar").css("width", "0%").attr("aria-valuenow", 0);
            $(".progress-bar").html("0%");
            $("#importModal").modal("hide");
            $("#excelcontent").val("");
          }, 2000);
          var total_imported = res.data;
          var get_imported = total_imported.split("/");

          if (get_imported[0] == 0 && get_imported[1] == 0) {
            _this.open_notif("warning", "Import", "No row to be import");
          } else if (get_imported[0] == 0 && get_imported[1] > 0) {
            _this.open_notif(
              "info",
              "Import",
              "All row already exist in the database"
            );
          } else if (get_imported[0] > 0 && get_imported[1] > 0) {
            _this.open_notif(
              "success",
              "Import",
              "Successfully imported: " + res.data
            );
            _this.getPatients();
          }
        })
        .catch(function (res) {
          _this.progressbar_import = false;
          $(".progress-bar").css("width", "0%").attr("aria-valuenow", 0);
          $(".progress-bar").html("0%");
          $("#excelcontent").val("");
          $("#importModal").modal("hide");
          _this.open_notif(
            "error",
            "Message",
            "FAILURE!! Something went wrong!"
          );
        });
    },
    formLoading: function () {
      const loading = this.$loading({
        lock: true,
        spinner: "el-icon-loading",
        target: "div.el-dialog",
      });
      loading.close();
    },
    masknumber: function (num) {
      num = parseFloat(num)
        .toFixed(2)
        .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
      return num;
    },
    setPage(val) {
      this.page = val;
    },
    handleEdit(index, row) {
      this.clearFields();
      this.form.id = row.id;
      this.form.formmode = "edit";

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
      this.formDialog("edit_medical");
    },
    handleView(index, row) {
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
    handleAddMedical(index, row) {
      this.dialogFormMedicalVisible = true;
      this.formMedical.formmode = "addMedical";
    },
    handleDelete(index, row) {
      var data = this.data;

      this.deletePatients(row.id, (res_value) => {
        if (res_value) {
          data.splice(data.indexOf(row), 1);
        }
      });
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
              .catch((error) => {
                this.errors = error.response.data.errors;
              });
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
              .catch((error) => {
                this.errors = error.response.data.errors;
              });
          }
          break;
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
    formDialog: function (id) {
     if (id == "import_data") {
        $("#importModal").modal({ backdrop: "static", keyboard: false });
      } else if (id == "export_data") {
        $("#exportModal").modal({ backdrop: "static", keyboard: false });
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
    deletePatients: function (id, res) {
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
              _this.open_notif("success", "Success", "Succesfully! Deleted");
              res(id);
            }
          });
        })
        .catch((action) => {
          var _this = this;
          _this.open_notif("info", "Message", "No changes");
        });
    },
  },
  mounted() {
    this.getPatients();
    this.loading = false;
  },
};
</script>

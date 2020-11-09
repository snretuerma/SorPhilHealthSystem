<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-hospital-user"></i>&nbsp;&nbsp;Patient List
                </span>
            </div>
            <el-dropdown
                @command="formDialog"
                style="float:right;margin-left:3px;"
            >
                <el-button size="medium"
                    >Excel<i class="el-icon-arrow-down el-icon--right"></i
                ></el-button>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item
                        icon="el-icon-upload2"
                        command="import_data"
                        >Import Data</el-dropdown-item
                    >
                    <el-dropdown-item
                        icon="el-icon-download"
                        command="export_data"
                        >Export Data</el-dropdown-item
                    >
                </el-dropdown-menu>
            </el-dropdown>
            <el-button
                style="float:right;margin-left:3px;"
                size="medium"
                @click="
                    dialogFormVisible = true;
                    form.formmode = 'add';
                    clearFields();
                "
                >Add</el-button
            >
        </div>
        <!-- End Header -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="listData">
                    <el-table-column
                        width="150"
                        label="PhilHealth No."
                        prop="philhealth_number"
                    ></el-table-column>
                    <el-table-column
                        width="250"
                        label="Name"
                        prop="name"
                    ></el-table-column>
                    <el-table-column
                        width="130"
                        label="Sex"
                        prop="sex"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Birthdate"
                        prop="birthdate"
                    ></el-table-column>
                    <el-table-column
                        width="180"
                        label="Marital Status"
                        prop="marital_status"
                    ></el-table-column>
                    <el-table-column width="250" align="right" fixed="right">
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
                                    @click="
                                        handleAddMedical(
                                            scope.$index,
                                            scope.row
                                        )
                                    "
                                ></el-button>
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
                                ></el-button>
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
                                ></el-button>
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
                                    @click="
                                        handleDelete(scope.$index, scope.row)
                                    "
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>

                <div style="text-align: center">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="total"
                    >
                    </el-pagination>
                </div>
                <!-- End table -->
            </div>
        </div>
        <!-- Card ends here -->

        <!-- Footer -->
        <hr />
        <div class="footer">
            <div class="containter-fluid">
                <div class="row text-center">
                    <span class="text-muted"
                        >&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System
                        2020</span
                    >
                </div>
            </div>
        </div>
        <!-- Footer ends -->

        <!-- Add Patient form -->
        <el-dialog
            title="Patient Details"
            :visible.sync="dialogFormVisible"
            center
            top="5vh"
            :close-on-press-escape="false"
            :close-on-click-modal="false"
        >
            <el-form :model="form" :rules="rules" ref="form">
                <el-form-item
                    label="Lastname"
                    :label-width="formLabelWidth"
                    prop="last_name"
                >
                    <el-input
                        v-model="form.last_name"
                        autocomplete="off"
                    ></el-input>
                    <span
                        class="font-italic text-danger"
                        v-if="errors.last_name"
                        ><small>{{ errors.last_name[0] }}</small></span
                    >
                </el-form-item>
                <el-form-item
                    label="Firstname"
                    :label-width="formLabelWidth"
                    prop="first_name"
                >
                    <el-input
                        v-model="form.first_name"
                        autocomplete="off"
                    ></el-input>
                    <span
                        class="font-italic text-danger"
                        v-if="errors.first_name"
                        ><small>{{ errors.first_name[0] }}</small></span
                    >
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
                    <span
                        class="font-italic text-danger"
                        v-if="errors.middle_name"
                        ><small>{{ errors.middle_name[0] }}</small></span
                    >
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
                <el-form-item
                    label="Sex"
                    :label-width="formLabelWidth"
                    prop="sex"
                >
                    <el-select v-model="form.sex" placeholder="Please select">
                        <el-option label="Male" value="0"></el-option>
                        <el-option label="Female" value="1"></el-option>
                    </el-select>
                    <br />
                    <span class="font-italic text-danger" v-if="errors.sex"
                        ><small>{{ errors.sex[0] }}</small></span
                    >
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
                    <span
                        class="font-italic text-danger"
                        v-if="errors.birthdate"
                        ><small>{{ errors.birthdate[0] }}</small></span
                    >
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
                    <br />
                    <span
                        class="font-italic text-danger"
                        v-if="errors.marital_status"
                        ><small>{{ errors.marital_status[0] }}</small></span
                    >
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
                    <span
                        class="font-italic text-danger"
                        v-if="errors.philhealth_number"
                        ><small>{{ errors.philhealth_number[0] }}</small></span
                    >
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

        <!-- Add medical form -->
        <el-dialog
            title="Add Medical Record"
            :visible.sync="dialogFormMedicalVisible"
            top="0vh"
        >
            <el-form :model="formMedical" :rules="rules" ref="form">
                <el-form-item
                    label="Admission Date"
                    :label-width="formLabelWidth"
                    prop="admission_date"
                >
                    <el-date-picker
                        type="date"
                        placeholder="Pick a date"
                        v-model="formMedical.admission_date"
                        style="width: 100%"
                        value-format="yyyy-MM-dd"
                    ></el-date-picker>
                </el-form-item>

                <el-form-item
                    label="Discharge Date"
                    :label-width="formLabelWidth"
                    prop="discharge_date"
                >
                    <el-date-picker
                        type="date"
                        placeholder="Pick a date"
                        v-model="formMedical.discharge_date"
                        style="width: 100%"
                        value-format="yyyy-MM-dd"
                    ></el-date-picker>
                </el-form-item>
                <el-form-item
                    label="Final Diagnosis"
                    :label-width="formLabelWidth"
                    prop="final_diagnosis"
                >
                    <el-input
                        v-model="formMedical.final_diagnosis"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>
                <el-form-item
                    label="Record Type"
                    :label-width="formLabelWidth"
                    prop="record_type"
                >
                    <el-input
                        v-model="formMedical.record_type"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>
                <el-form-item
                    label="Total Fee"
                    :label-width="formLabelWidth"
                    prop="total_fee"
                >
                    <el-input
                        v-model="formMedical.total_fee"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogMedicalFormVisible = false"
                    >Cancel</el-button
                >
                <el-button
                    v-if="this.formMedical.formmode == 'insert_data'"
                    type="primary"
                    @click="
                        addMedicalRecord();
                        formLoading();
                    "
                    >Save</el-button
                >
            </span>
        </el-dialog>
        <!-- Add medical form ends here-->

        <!-- Show Patient Details -->
        <el-dialog title="Patient Info" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column
                    property="philhealth_number"
                    label="PhilHealth No."
                    width="120"
                ></el-table-column>
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
                    width="120"
                ></el-table-column>
                <el-table-column
                    property="marital_status"
                    label="Marital Status"
                    width="120"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Patient Details -->

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
                        <h5 class="modal-title" id="ModalLabel">
                            Export Patient
                        </h5>
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
                        <input
                            type="hidden"
                            name="e_action"
                            id="e_action"
                            value="PatientExport"
                        />
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
                            <button
                                type="submit"
                                name="upload"
                                class="btn btn-primary"
                            >
                                Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Export excel end-->

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
                        <h5 class="modal-title" id="ModalLabel">
                            Import Patient
                        </h5>
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
                        <input
                            type="hidden"
                            name="i_action"
                            id="i_action"
                            value="PatientImport"
                        />
                        <div class="modal-body">
                            <div class="form-group">
                                <label
                                    >Select excel file for upload (.csv)</label
                                ><br />
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
    </div>
</template>

<script>
"use strict";
import constants from "../../constants.js";
export default {
    data() {
        return {
            filteredLength: 0,
            page: 1,
            pageSize: 10,
            loading: true,
            search: "",
            data: [],
            errors: [],
            staff: [],
            dialogFormVisible_import_excel: false,
            progressbar_import: false,
            enableUpload: false,
            dialogTableVisible: false,
            dialogFormVisible: false,
            dialogFormMedicalVisible: false,
            formLabelWidth: "130px",
            // Validation
            rules: {
                last_name: [
                    {
                        required: true,
                        message: "Lastname is required.",
                        trigger: "blur"
                    }
                ],
                first_name: [
                    {
                        required: true,
                        message: "Firstname is required.",
                        trigger: "blur"
                    }
                ],
                middle_name: [
                    {
                        required: true,
                        message: "Middlename is required.",
                        trigger: "blur"
                    }
                ],
                sex: [
                    {
                        required: true,
                        message: "Sex is required.",
                        trigger: "change"
                    }
                ],
                birthdate: [
                    {
                        required: true,
                        message: "Please pick a date.",
                        trigger: "change"
                    }
                ],
                marital_status: [
                    {
                        required: true,
                        message: "Please select a marital status.",
                        trigger: "change"
                    }
                ],
                philhealth_number: [
                    {
                        required: true,
                        message: "PhilHealth No. is required.",
                        trigger: "blur"
                    }
                ]
            },
            // Add Patient form
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
                edit_object_index: ""
            },
            // Edit form check
            form_check: {
                last_name: "",
                first_name: "",
                middle_name: "",
                name_suffix: "",
                sex: "",
                birthdate: "",
                marital_status: "",
                philhealth_number: "",
                name: ""
            },
            // Add Medical form
            formMedical: {
                admission_date: "",
                discharge_date: "",
                final_diagnosis: "",
                patient_id: "",
                record_type: "",
                total_fee: "",
                is_private: "",
                is_public: ""
            },
            // Show info data
            gridData: [
                {
                    philhealth_number: "",
                    name: "",
                    sex: "",
                    birthdate: "",
                    marital_status: ""
                }
            ]
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.data.length;
                return this.data;
            }
            this.page = 1;
            return this.data.filter(
                data =>
                    data.first_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.last_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.philhealth_number
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;

            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        }
    },
    methods: {
        addMedicalRecord() {
            axios
                .post("/user/medicalrecord_add", this.formMedical)
                .then(response => {
                    if (response.status > 199 && response.status < 203) {
                        this.open_notif(
                            "success",
                            "Success",
                            "Medical Record Save!"
                        );
                        this.dialogFormMedicalVisible = false;
                        this.formMedical = [];
                    }
                })
                .catch(function(error) {});
        },
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
            formData.append("i_action", $("#i_action").val());
            formData.append("patients[]", $("#excelcontent").get(0).files[0]);
            axios
                .post("patients_import", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    },
                    onUploadProgress: function(progressEvent) {
                        this.uploadPercentage = parseInt(
                            Math.round(
                                (progressEvent.loaded * 100) /
                                    progressEvent.total
                            )
                        );

                        $(".progress-bar")
                            .css("width", this.uploadPercentage + "%")
                            .attr("aria-valuenow", this.uploadPercentage);
                        $(".progress-bar").html(this.uploadPercentage + "%");
                    }.bind(this)
                })
                .then(function(res) {
                    setTimeout(function() {
                        _this.progressbar_import = false;
                        $(".progress-bar")
                            .css("width", "0%")
                            .attr("aria-valuenow", 0);
                        $(".progress-bar").html("0%");
                        $("#importModal").modal("hide");
                        $("#excelcontent").val("");
                    }, 2000);
                    var total_imported = res.data;
                    var get_imported = total_imported.split("/");

                    if (get_imported[0] == 0 && get_imported[1] == 0) {
                        _this.open_notif(
                            "warning",
                            "Import",
                            "No row to be import"
                        );
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
                .catch(function(res) {
                    _this.progressbar_import = false;
                    $(".progress-bar")
                        .css("width", "0%")
                        .attr("aria-valuenow", 0);
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
        formDialog: function(id) {
            if (id == "import_data") {
                $("#importModal").modal({
                    backdrop: "static",
                    keyboard: false
                });
            } else if (id == "export_data") {
                $("#exportModal").modal({
                    backdrop: "static",
                    keyboard: false
                });
            }
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        formLoading: function() {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog"
            });
            loading.close();
        },
        getPatients: function() {
            axios
                .get("patients_get")
                .then(response => {
                    response.data.forEach(element => {
                        this.buildPatientData(element);
                    });
                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
        },
        handleAddMedical(index, row) {
            this.dialogFormMedicalVisible = true;
            this.formMedical.formmode = "insert_data";
            this.formMedical.patient_id = row.id;
        },
        handleView(index, row) {
            this.dialogTableVisible = true;
            this.gridData[0].philhealth_number = row.philhealth_number;
            this.gridData[0].name = this.buildName(
                row.first_name,
                row.middle_name,
                row.last_name,
                row.name_suffix
            );
            this.gridData[0].sex = row.sex;
            this.gridData[0].birthdate = row.birthdate;
            this.gridData[0].marital_status = row.marital_status;
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

            this.form_check.last_name = row.last_name;
            this.form_check.first_name = row.first_name;
            this.form_check.middle_name = row.middle_name;
            this.form_check.name_suffix = row.name_suffix;
            this.form_check.sex = row.sex;
            this.form_check.birthdate = row.birthdate;
            this.form_check.marital_status = row.marital_status;
            this.form_check.philhealth_number = row.philhealth_number;

            this.form_check.name =
                this.form_check.last_name +
                ", " +
                this.form_check.name_suffix +
                " " +
                this.form_check.first_name +
                " " +
                this.form_check.middle_name.slice(0, 1) +
                ". ";
            this.dialogFormVisible = true;
        },
        handleDelete(index, row) {
            var data = this.data;

            this.deletePatient(row.id, res_value => {
                if (res_value) {
                    data.splice(data.indexOf(row), 1);
                }
            });
        },
        patientFunctions: function(mode) {
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
                            .then(response => {
                                if (
                                    response.status > 199 &&
                                    response.status < 203
                                ) {
                                    response.data.name =
                                        this.form.last_name +
                                        ", " +
                                        this.form.name_suffix +
                                        " " +
                                        this.form.first_name +
                                        " " +
                                        this.form.middle_name.slice(0, 1) +
                                        ". ";
                                    response.data.sex =
                                        constants.sex[Number(this.form.sex)];
                                    response.data.marital_status =
                                        constants.marital_status[
                                            Number(this.form.marital_status)
                                        ];

                                    this.data.push(response.data);
                                    this.dialogFormVisible = false;
                                    this.open_notif(
                                        "success",
                                        "Success",
                                        "Patient added successfully"
                                    );
                                } else {
                                    this.open_notif(
                                        "error",
                                        "System",
                                        "Failed to add patient"
                                    );
                                }
                            })
                            .catch(error => {
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
                        this.form.marital_status ==
                            this.form_check.marital_status &&
                        this.form.philhealth_number ==
                            this.form_check.philhealth_number
                    ) {
                        this.open_notif("info", "Message", "No Changes");
                    } else {
                        if (this.form.sex == "Male") {
                            this.form.sex = 0;
                        } else if (this.form.sex == "Female") {
                            this.form.sex = 1;
                        }
                        if (this.form.marital_status == "Single") {
                            this.form.marital_status = 0;
                        } else if (this.form.marital_status == "Married") {
                            this.form.marital_status = 1;
                        } else if (this.form.marital_status == "Divorced") {
                            this.form.marital_status = 2;
                        } else if (this.form.marital_status == "Widowed") {
                            this.form.marital_status = 3;
                        } else if (
                            this.form.marital_status ==
                            "Others/Prefer Not to Say"
                        ) {
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
                            .then(response => {
                                if (
                                    response.status > 199 &&
                                    response.status < 203
                                ) {
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
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].sex =
                                        constants.sex[Number(this.form.sex)];
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].birthdate = this.form.birthdate;
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].marital_status =
                                        constants.marital_status[
                                            Number(this.form.marital_status)
                                        ];
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].philhealth_number = this.form.philhealth_number;
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].name = this.form.name;
                                }
                            })
                            .catch(error => {
                                this.errors = error.response.data.errors;
                            });
                    }
                    break;
            }
        },
        deletePatient: function(id, res) {
            this.$confirm(
                "Are you sure you want to delete?",
                "Confirm Delete",
                {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    var _this = this;
                    axios.post("patient_delete/" + id).then(function(response) {
                        if (response.status > 199 && response.status < 203) {
                            _this.open_notif(
                                "success",
                                "Success",
                                "Deleted Successfully"
                            );
                            res(id);
                        }
                    });
                })
                .catch(action => {
                    this.open_notif("info", "Cancelled", "No Changes");
                });
        },
        open_notif: function(status, title, message) {
            if (status == "success") {
                this.$notify.success({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "error") {
                this.$notify.error({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "info") {
                this.$notify.info({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "warning") {
                this.$notify.warning({
                    title: title,
                    message: message,
                    offset: 0
                });
            }
        },
        clearFields: function() {
            this.form.last_name = "";
            this.form.first_name = "";
            this.form.middle_name = "";
            this.form.name_suffix = "";
            this.form.sex = "";
            this.form.birthdate = "";
            this.form.marital_status = "";
            this.form.philhealth_number = "";
        },
        buildName: function(first_name, middle_name, last_name, suffix) {
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
        assignSex: function(sex_value) {
            var sex;
            switch (sex_value) {
                case 0:
                    sex = "Male";
                    break;
                case 1:
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
        assignMaritalStatus: function(marital_status_value) {
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
        buildPatientData: function(element) {
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
            element.marital_status = this.assignMaritalStatus(
                element.marital_status
            );
        },
        getStaff() {
            axios
                .get("/user/personnel_get")
                .then(response => {
                    response.data.forEach(element => {
                        if (element.designation == 0) {
                            if (element.is_private == 0) {
                                this.formMedical.is_private++;
                            } else {
                                this.formMedical.is_public++;
                            }
                        }
                    });
                })
                .catch(function(error) {});
        }
    },
    mounted() {
        this.getPatients();
        this.getStaff();
    }
};
</script>

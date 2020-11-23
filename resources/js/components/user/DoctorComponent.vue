<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-user-md"></i>&nbsp;&nbsp;Staff List
                </span>
            </div>
        </div>
        <!-- End Header -->

        <!-- Search Box -->
        <div class="row" style="margin-bottom: 10px">
            <div class="col-5">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
            <div class="col-7">
                <el-button
                    class="btn-action"
                    size="medium"
                    @click="handleSearchClick"
                >
                    Add
                </el-button>
                <el-dropdown @command="formDialog" class="btn-action">
                    <el-button size="medium">
                        Excel
                        <i class="el-icon-arrow-down el-icon--right" />
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item
                            icon="el-icon-upload2"
                            command="import_data"
                        >
                            Import Data
                        </el-dropdown-item>
                        <el-dropdown-item
                            icon="el-icon-download"
                            command="export_data"
                        >
                            Export Data
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>
        <!-- Search End -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="listData">
                    <el-table-column label="Name" prop="name"></el-table-column>
                    <el-table-column
                        label="Employment Type"
                        prop="is_parttime"
                        align="center"
                        :formatter="employmentType"
                    ></el-table-column>
                    <el-table-column
                        label="Employment Status"
                        prop="is_active"
                        align="center"
                        :formatter="employmentStatus"
                    ></el-table-column>
                    <el-table-column width="135" align="center" fixed="right">
                        <template slot="header"> Action </template>
                        <template slot-scope="scope">
                            <!-- <el-tooltip
                                class="item"
                                effect="light"
                                content="View"
                                placement="top"
                                :enterable="false"
                            >
                                <el-button
                                    size="mini"
                                    type="info"
                                    icon="el-icon-info"
                                    circle
                                    @click="handleView(scope.$index, scope.row)"
                                ></el-button>
                            </el-tooltip> -->
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="Edit"
                                placement="top"
                                :enterable="false"
                            >
                                <el-button
                                    size="mini"
                                    type="primary"
                                    icon="el-icon-edit"
                                    circle
                                    @click="handleEdit(scope.$index, scope.row, 'edit')"
                                ></el-button>
                            </el-tooltip>
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="Delete"
                                placement="top"
                                :enterable="false"
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
                    <span class="text-muted">
                        &nbsp;&nbsp;&nbsp;&nbsp;©PF Management System 2020
                    </span>
                </div>
            </div>
        </div>
        <!-- Footer ends -->

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
                            Import Staffs
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
                        action="/personnels_import"
                    >
                        <input type="hidden" name="" id="" />
                        <input
                            type="hidden"
                            name="i_action"
                            id="i_action"
                            value="PersonnelImport"
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
                                    name="personnels"
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
                        <h5 class="modal-title" id="ModalLabel">
                            Export Staffs
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
                        action="personnels_export/"
                    >
                        <input type="hidden" name="" id="" />
                        <input
                            type="hidden"
                            name="e_action"
                            id="e_action"
                            value="PersonnelExport"
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

        <!-- Add/Edit Personnel form -->
        <add-edit-doctor-dialog :open_dialog='open_dialog' :index_data='row_data' :form_type='form_type'></add-edit-doctor-dialog>
        <!-- Show Personnel Details -->
    </div>
</template>

<script>
"use strict";
import AddEditDoctorDialog from './subcomponents/AddEditDoctorDialog';
import constants from "../../constants.js";
export default {
    components: {
        AddEditDoctorDialog
    },
    data() {
        return {
            page: 1,
            pageSize: 10,
            loading: true,
            search: "",
            data: [],
            errors: [],
            dialogFormVisible_import_excel: false,
            progressbar_import: false,
            enableUpload: false,
            dialogTableVisible: false,
            dialogFormVisible: false,
            formLabelWidth: "150px",
            progressbar_import: false,
            enableUpload: false,
            form_type: '',
            open_dialog: false,
            row_data: '',
            // Add Personnel form
            form: {
                id: "",
                name: "",
                is_parttime: "",
                designation: "",
                formmode: "",
                edit_object_index: "",
            },
            // Edit Personnel form check
            form_check: {
                name: "",
                is_parttime: "",
            },
            // View info data
            gridData: [
                {
                    name: "",
                    is_parttime: "",
                    is_active: "",
                },
            ],
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
                (data) => data.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;

            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        },
    },
    methods: {
        employmentStatus(row, column) {
            if(row.is_active) {
                return 'Active'
            }
            return 'Inactive'
        },
        employmentType(row, column) {
            if(row.is_parttime) {
                return 'Part-time'
            }
            return 'Full-time'
        },
        handleEdit(data, form_type) {
            this.form_type = form_type;
            this.open_dialog = true;
            this.row_data = data;
        },
        handleSearchClick() {
            this.dialogFormVisible = true;
            this.form.formmode = "add";
            this.clearFields();
        },
        triggerAdd(row) {
            this.$emit("add-trigger", row);
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        formLoading: function () {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog",
            });
            loading.close();
        },
        getDoctors: function () {
            axios.get("doctors_get")
                .then((response) => {
                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function (error) {

                });
        },
        handleDelete(index, row) {
            var data = this.data;

            this.deletePersonnel(row.id, (res_value) => {
                if (res_value) {
                    data.splice(data.indexOf(row), 1);
                }
            });
        },
        personnelFunctions: function (mode) {
            switch (mode) {
                case "add":
                    if (
                        this.form.last_name == "" ||
                        this.form.first_name == "" ||
                        this.form.middle_name == "" ||
                        this.form.is_private == "" ||
                        this.form.is_parttime == "" ||
                        this.form.designation == "" ||
                        this.form.sex == "" ||
                        this.form.birthdate == ""
                    ) {
                        this.open_notif(
                            "info",
                            "Message",
                            "Required fields were missing values."
                        );
                    } else {
                        axios
                            .post("add_personnel", this.form)
                            .then((response) => {
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
                                    response.data.is_private =
                                        constants.is_private[
                                            Number(this.form.is_private)
                                        ];
                                    response.data.is_parttime =
                                        constants.is_parttime[
                                            Number(this.form.is_parttime)
                                        ];
                                    response.data.designation =
                                        constants.designation[
                                            Number(this.form.designation)
                                        ];
                                    response.data.sex =
                                        constants.sex[Number(this.form.sex)];
                                    this.data.push(response.data);
                                    this.dialogFormVisible = false;
                                    this.open_notif(
                                        "success",
                                        "Success",
                                        "Staff added successfully"
                                    );
                                } else {
                                    this.open_notif(
                                        "error",
                                        "System",
                                        "Failed to add personnel"
                                    );
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
                        this.form.is_private == this.form_check.is_private &&
                        this.form.is_parttime == this.form_check.is_parttime &&
                        this.form.designation == this.form_check.designation &&
                        this.form.sex == this.form_check.sex &&
                        this.form.birthdate == this.form_check.birthdate
                    ) {
                        this.open_notif("info", "Message", "No Changes");
                    } else {
                        if (this.form.sex == "Male") {
                            this.form.sex = 0;
                        } else if (this.form.sex == "Female") {
                            this.form.sex = 1;
                        }
                        if (this.form.is_private == "Private") {
                            this.form.is_private = 1;
                        } else if (this.form.is_private == "Non-private") {
                            this.form.is_private = 0;
                        }
                        if (this.form.is_parttime == "Full-time") {
                            this.form.is_parttime = 1;
                        } else if (this.form.is_parttime == "Part-time") {
                            this.form.is_parttime = 0;
                        }
                        if (this.form.designation == "Medical") {
                            this.form.designation = 1;
                        } else if (this.form.designation == "Non-medical") {
                            this.form.designation = 0;
                        }
                        this.form.name_suffix.trim();
                        if (this.form.name_suffix == null) {
                            this.form.name_suffix = "";
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
                            .post("edit_personnel/" + this.form.id, this.form)
                            .then((response) => {
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
                                    ].is_private =
                                        constants.is_private[
                                            Number(this.form.is_private)
                                        ];
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].is_parttime =
                                        constants.is_parttime[
                                            Number(this.form.is_parttime)
                                        ];
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].designation =
                                        constants.designation[
                                            Number(this.form.designation)
                                        ];
                                    this.data[
                                        parseInt(this.form.edit_object_index)
                                    ].birthdate = this.form.birthdate;
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
        deletePersonnel: function (id, res) {
            this.$confirm(
                "Are you sure you want to delete?",
                "Confirm Delete",
                {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    var _this = this;
                    axios
                        .post(`personnel_delete/${id}`)
                        .then(function (response) {
                            if (
                                response.status > 199 &&
                                response.status < 203
                            ) {
                                _this.open_notif(
                                    "success",
                                    "Success",
                                    "Deleted Successfully"
                                );
                                res(id);
                            }
                        });
                })
                .catch((action) => {
                    this.open_notif("info", "Cancelled", "No Changes");
                });
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
            this.form.is_private = "";
            this.form.is_parttime = "";
            this.form.designation = "";
            this.form.sex = "";
            this.form.birthdate = "";
        },
        formDialog: function (id) {
            if (id == "import_data") {
                $("#importModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            } else if (id == "export_data") {
                $("#exportModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            }
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
            formData.append("personnels[]", $("#excelcontent").get(0).files[0]);
            axios
                .post("personnels_import", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    onUploadProgress: function (progressEvent) {
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
                    }.bind(this),
                })
                .then(function (res) {
                    setTimeout(function () {
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
                        _this.getPersonnel();
                    }
                })
                .catch(function (res) {
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
                        "FAILURE!! Something went wrong!" + res
                    );
                });
        },
        validateBd($event) {
            var date = new Date();
            var year = date.getFullYear();
            var mon = date.getMonth() + 1;
            var day = date.getDate();
            if (mon < 10) {
                mon = "0" + mon;
            }
            if (day < 10) {
                day = "0" + day;
            }
            var selected = $event;
            var compare = selected.split("-");
            if (
                year + "" + mon + "" + day <
                compare[0] + compare[1] + compare[2]
            ) {
                this.form.birthdate = "";
                this.open_notif(
                    "info",
                    "Invalid",
                    "The Date of Birth should not be greater than today"
                );
            }
        },
    },
    mounted() {
        this.getDoctors();
    },
};
</script>

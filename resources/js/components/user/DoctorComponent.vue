<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-user-md"></i>&nbsp;&nbsp;Doctors
                </span>
            </div>
        </div>
        <!-- End Header -->
        <div id="search-box" class="row">
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    placeholder="Type to search"
                />
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <el-button
                            class="btn-action block-button"
                            @click="handleAddButtonClick"
                        >
                            Add
                        </el-button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <el-dropdown
                            @command="formDialog"
                            class="block-button btn-action"
                        >
                            <el-button>
                                Excel
                                <i class="el-icon-arrow-down el-icon--right" />
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item
                                    icon="el-icon-upload2"
                                    command="import_data"
                                >
                                    Upload Data
                                </el-dropdown-item>
                                <el-dropdown-item
                                    icon="el-icon-download"
                                    command="export_data"
                                >
                                    Download ...
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <el-table v-loading="loading" :data="tableData">
                            <el-table-column
                                label="Name"
                                prop="name"
                            ></el-table-column>
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
                            <el-table-column
                                width="135"
                                align="center"
                                fixed="right"
                            >
                                <template slot="header"> Action </template>
                                <template slot-scope="scope">
                                    <el-tooltip
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
                                            @click="handleView(scope.row)"
                                        ></el-button>
                                    </el-tooltip>
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
                                            @click="handleEdit(scope.row)"
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
                                            @click="handleDelete(scope.row)"
                                        ></el-button>
                                    </el-tooltip>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <el-pagination
                            class="align-middle"
                            background
                            layout="prev, pager, next"
                            @current-change="handleCurrentChange"
                            :page-size="page_size"
                            :total="total"
                        />
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            title="Doctor Details"
            :visible.sync="show_dialog"
            center
            :close-on-press-escape="false"
            :close-on-click-modal="false"
        >
            <el-form :model="form" :rules="rules" ref="doctors_form">
                <el-row>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            label="Last Name"
                            prop="last_name"
                            ref="form_last_name"
                        >
                            <el-input
                                v-model="form.last_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span
                                class="font-italic text-danger"
                                v-if="errors.last_name"
                            >
                                <small>{{ errors.last_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            label="First Name"
                            prop="first_name"
                            ref="form_first_name"
                        >
                            <el-input
                                v-model="form.first_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span
                                class="font-italic text-danger"
                                v-if="errors.first_name"
                            >
                                <small>{{ errors.first_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            label="Middle Name"
                            prop="middle_name"
                            ref="form_middle_name"
                        >
                            <el-input
                                v-model="form.middle_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span
                                class="font-italic text-danger"
                                v-if="errors.middle_name"
                            >
                                <small>{{ errors.middle_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            label="Suffix"
                            prop="suffix"
                            ref="form_suffix"
                        >
                            <el-input
                                v-model="form.suffix"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col>
                        <el-form-item label="Full Name" prop="name">
                            <el-input
                                v-model="form.name"
                                autocomplete="off"
                                :readonly="true"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            prop="is_parttime"
                            label="Employment Type"
                        >
                            <br />
                            <el-radio-group
                                v-model="form.is_parttime"
                                ref="form_is_parttime"
                            >
                                <div class="row">
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 col-xl-6"
                                    >
                                        <el-radio :label="false"
                                            >Full-time</el-radio
                                        >
                                    </div>
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 col-xl-6"
                                    >
                                        <el-radio :label="true"
                                            >Part-time</el-radio
                                        >
                                    </div>
                                </div>
                            </el-radio-group>
                            <span
                                class="font-italic text-danger"
                                v-if="errors.is_parttime"
                            >
                                <small>{{ errors.is_parttime[0] }}</small>
                            </span>
                        </el-form-item>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item
                            prop="is_active"
                            label="Employment Status"
                        >
                            <br />
                            <el-radio-group
                                v-model="form.is_active"
                                ref="form_is_active"
                            >
                                <div class="row">
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 col-xl-6"
                                    >
                                        <el-radio :label="true"
                                            >Active</el-radio
                                        >
                                    </div>
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 col-xl-6"
                                    >
                                        <el-radio :label="false"
                                            >Inactive</el-radio
                                        >
                                    </div>
                                </div>
                            </el-radio-group>
                            <span
                                class="font-italic text-danger"
                                v-if="errors.is_active"
                            >
                                <small>{{ errors.is_active[0] }}</small>
                            </span>
                        </el-form-item>
                    </div>
                </div>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="show_dialog = false">Cancel</el-button>
                <el-button
                    v-if="form.form_type == 'add'"
                    type="primary"
                    @click="doctorsFormAction()"
                >
                    Save
                </el-button>
                <el-button v-else type="primary" @click="doctorsFormAction()">
                    Save Changes
                </el-button>
            </span>
        </el-dialog>

        <el-dialog
            :title="dialogViewTitle"
            :visible.sync="show_doctor_summary"
            width="90%"
            top="5vh"
            :close-on-press-escape="false"
            :close-on-click-modal="false"
        >
            <el-row>
                <el-col>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-8 col-xl-20">
                            <el-form ref="form">
                                <el-form-item prop="batch">
                                    <el-select
                                        ref="defaultValue"
                                        :required="true"
                                        v-model="value"
                                        class="block-button"
                                        size="large"
                                        value-key="batch"
                                        multiple
                                        :multiple-limit="1"
                                        filterable
                                        default-first-option
                                        allow-create
                                        @change="changes"
                                    >
                                        <el-option
                                            v-for="item in batch"
                                            :key="item.batch"
                                            :label="item.label"
                                            :value="item.batch"
                                        >
                                            {{ item.batch }}</el-option
                                        >
                                    </el-select>
                                </el-form-item>
                            </el-form>
                        </el-col>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <el-button type="primary">Export</el-button>
                        </el-col>
                    </el-row>
                    <el-table :data="batch_list" border>
                        <el-table-column
                            fixed
                            prop="patient_name"
                            min-width="200"
                            label="Patient Name"
                        >
                        </el-table-column>
                        <el-table-column label="Confinement Period">
                            <el-table-column
                                prop="admission_date"
                                label="Admission Date"
                                min-width="150"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="discharge_date"
                                label="Discharge Date"
                                min-width="140"
                            >
                            </el-table-column>
                        </el-table-column>
                        <el-table-column
                            prop="grossPF"
                            label="Gross PF"
                            min-width="80"
                            :formatter="formatNumber"
                        >
                        </el-table-column>
                        <el-table-column
                            prop="netPF"
                            label="Net PF"
                            min-width="80"
                            :formatter="formatNumber"
                        >
                        </el-table-column>
                        <el-table-column
                            prop="pooled_record.full_time_individual_fee"
                            label="Pooled"
                            min-width="80"
                            :formatter="formatNumber"
                        >
                        </el-table-column>
                        <el-table-column
                            prop="pivot.professional_fee"
                            label="Professional fee"
                            min-width="90"
                            :formatter="formatNumber"
                        >
                        </el-table-column>
                        <el-table-column :label="dialogViewTitle">
                            <el-table-column prop="#" label="AP">
                            </el-table-column>
                            <el-table-column prop="#" label="REF">
                            </el-table-column>
                            <el-table-column prop="#" label="ANES">
                            </el-table-column>
                            <el-table-column prop="#" label="CO_MGT">
                            </el-table-column>
                            <el-table-column prop="#" label="ADMIT">
                            </el-table-column>
                        </el-table-column>
                    </el-table>
                    <!-- </el-collapse-item>
                        <div v-if="batch_list.length == 0" class="center">
                            <strong>
                                No record found
                            </strong>
                        </div>
                    </el-collapse> -->
                </el-col>
            </el-row>
        </el-dialog>
        <el-dialog
            :title="dialogtitle"
            :visible.sync="dialogExcelFile"
            :fullscreen="fullscreen"
        >
            <el-row v-show="!isimport">
                <el-col>
                    <el-form>
                        <el-form-item>
                            <el-button @click="getTemplate"
                                >Download Sample Excel File /
                                Template</el-button
                            >
                            <el-button type="primary" @click="exportExcel"
                                >Download Data</el-button
                            >
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-row v-show="isimport">
                <el-form>
                    <el-form-item>
                        <el-upload
                            class="upload-demo"
                            ref="upload"
                            action="import_doctor_record"
                            :auto-upload="false"
                            :on-change="fileData"
                            :limit="1"
                            :on-exceed="handleExceedFile"
                            :on-remove="handleRemoveFile"
                            accept=".xlsx"
                        >
                            <el-button slot="trigger" type="primary" plain
                                >select supported excel file .xlsx</el-button
                            >
                            <el-button
                                type="success"
                                @click="uploadToDatabase"
                                :disabled="!is_preview"
                                >upload to database</el-button
                            >
                        </el-upload>
                    </el-form-item>
                </el-form>
                <el-row v-show="is_preview && preview_data.length > 0">
                    <el-col>
                        <el-table :data="exceldata" style="width: 100%" border>
                            <el-table-column
                                fixed
                                prop="Physician_Name"
                                label="Physician Name"
                            >
                            </el-table-column>
                            <el-table-column prop="Is_active" label="Is active">
                            </el-table-column>
                            <el-table-column
                                prop="Is_parttime"
                                label="Is parttime"
                            >
                            </el-table-column>
                        </el-table>
                        <el-pagination
                            class="align-middle"
                            background
                            layout="prev, pager, next"
                            @current-change="handleCurrentChangeTable"
                            :page-size="page_size"
                            :total="tablelength"
                        />
                    </el-col>
                </el-row>
                <div v-show="!is_preview && preview_data.length > 0">
                    <el-alert
                        title="ERROR FOUND, Please see error log bellow"
                        type="error"
                        :closable="false"
                        show-icon
                    >
                    </el-alert>
                    <ul>
                        <dl>
                            <dt>HEADER</dt>
                            <ol>
                                <li
                                    v-for="item in excel_validation_error[0]"
                                    :key="item.id"
                                >
                                    <strong>{{ item.value }}</strong>
                                    {{ item.message }}
                                    <strong>[{{ item.cell_position }}]</strong>
                                </li>
                            </ol>
                            <dt>CONTENT</dt>
                            <ol>
                                <li
                                    v-for="item in excel_validation_error[1]"
                                    :key="item.id"
                                >
                                    <strong>{{ item.value }}</strong>
                                    {{ item.message }}
                                    <strong>[{{ item.cell_position }}]</strong>
                                </li>
                            </ol>
                        </dl>
                    </ul>
                </div>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
/**
 * TODO:
 *  1. Modify the edit function to a better one
 **/
"use strict";
import XLSX from "xlsx";
import _ from "lodash";
export default {
    data() {
        return {
            value: [],
            batch: [],
            doctors: [],
            doctor_id: '',
            temp: [],
            edit_object: "",
            errors: [],
            holder: [],
            form: {
                id: "",
                name: "",
                first_name: "",
                middle_name: "",
                last_name: "",
                suffix: "",
                is_parttime: false,
                is_active: true,
                form_type: ""
            },
            loading: true,
            page: 1,
            page_size: 10,
            rules: {
                first_name: [
                    {
                        required: true,
                        message: "First name is required",
                        trigger: "blur"
                    }
                ],
                middle_name: [
                    {
                        required: true,
                        message: "Middle name is required",
                        trigger: "blur"
                    }
                ],
                last_name: [
                    {
                        required: true,
                        message: "Last name is required",
                        trigger: "blur"
                    }
                ],
                is_parttime: [
                    {
                        required: true,
                        message: "Please select employment type",
                        trigger: "change"
                    }
                ],
                is_active: [
                    {
                        required: true,
                        message: "Please select employment status",
                        trigger: "change"
                    }
                ]
            },
            search: "",
            show_dialog: false,
            show_doctor_summary: false,
            total: "",
            dialogExcelFile: false,
            sheet_length: "",
            tablepage: 1,
            page_size: 10,
            tablelength: 0,
            preview_data: [],
            exceldata: [],
            excel_validation_error: [[], []],
            doctor_list_compress: [],
            doctor_export: [],
            is_preview: false,
            fullscreen: true,
            dialogtitle: "Import Excel",
            isimport: true,
            batch_list: [],
            form1: {
                doctor_id: "",
                record_id: [],
                batch: ""
            },
            activeName: "1",
            dialogViewTitle: "Doctor Record"
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.doctors.length;
                return this.doctors;
            }
            this.page = 1;
            return this.doctors.filter(data =>
                data.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        tableData() {
            this.total = this.searching.length;
            return this.searching.slice(
                this.page_size * this.page - this.page_size,
                this.page_size * this.page
            );
        }
    },
    methods: {
        getCoPhysicians:function (id) {
            var temp = [];
            this.batch_list.forEach(el =>{
                temp.push(el.id);
            });
            this.form1.doctor_id = id;
            this.form1.record_id = temp;
            this.form1.batch = this.value[0];
            axios.post("getCoPhysicians", this.form1).then(response => {

            }).catch(function(error) {});
        },
        getBatch() {
            axios
                .get("get_batch")
                .then(response => {
                    this.batch = response.data;
                    this.value[0] = response.data[0].batch;
                    this.first_batch = response.data[0].batch;
                })
                .catch(function(error) {});
        },
        changes() {
            if (this.value != "") {
                this.batch_list = [];
                var batch = [];
                var count = 0;
                this.holder.credit_records.forEach(el => {
                    el.netPF = 0;
                    el.grossPF = 0;

                    if (el.pooled_record != null) {
                        el.netPF =
                            Number(el.pivot.professional_fee) +
                            Number(el.pooled_record.full_time_individual_fee);
                        el.grossPF = el.netPF * 2;
                    } else el.netPF = Number(el.pivot.professional_fee + 0);
                    el.grossPF = el.netPF * 2;
                    if (el.batch == this.value[0]) {
                        this.batch_list.push(el);
                    }
                });

                this.getBatch();
            }
        },
        getDoctors() {
            var _this = this;
            axios.get("get_doctors").then(response => {
                this.doctors = response.data;
                this.loading = false;
                response.data.forEach(el => {
                    _this.doctor_list_compress.push(
                        _this.trimToCompare(el.name)
                    );
                });
            });
        },
        handleView(row_data) {
            this.dialogViewTitle = row_data.name.toUpperCase();
            this.show_doctor_summary = true;
            this.batch_list = [];
            var batch = [];
            var count = 0;
            this.doctor_id = row_data.id;
            row_data.credit_records.forEach(el => {
                el.netPF = 0;
                el.grossPF = 0;

                if (el.pooled_record != null) {
                    el.netPF =
                        Number(el.pivot.professional_fee) +
                        Number(el.pooled_record.full_time_individual_fee);
                    el.grossPF = el.netPF * 2;
                } else el.netPF = Number(el.pivot.professional_fee + 0);
                el.grossPF = el.netPF * 2;
                if (el.batch == this.value[0]) {
                    this.batch_list.push(el);
                }

            });

            this.getBatch();
            this.getCoPhysicians(this.doctor_id);
            this.holder = row_data;
        },
        doctorsFormAction() {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog"
            });
            switch (this.form.form_type) {
                case "add":
                    if (
                        this.form.name == "" ||
                        this.form.is_parttime === "" ||
                        this.is_active === ""
                    ) {
                        this.$notify({
                            type: "info",
                            title: "Adding Doctor Failed",
                            message: "All fields are required",
                            offset: 0
                        });
                        this.show_dialog = false;
                        this.formResetFields();
                        loading.close();
                    } else {
                        this.form.name = this.form.name.trim();
                        if (this.testName(this.form.name)) {
                            axios
                                .post("add_doctor", this.form)
                                .then(response => {
                                    this.doctors.push(response.data);
                                    this.doctor_list_compress.push(
                                        this.trimToCompare(this.form.name)
                                    );
                                    this.show_dialog = false;
                                    this.formResetFields();
                                    loading.close();
                                    this.$notify({
                                        type: "success",
                                        title: "Adding Doctor Successful",
                                        message: `Successfully added ${response.data.name}`,
                                        offset: 0
                                    });
                                })
                                .catch(error => {
                                    this.show_dialog = false;
                                    this.formResetFields();
                                    loading.close();
                                    this.$notify({
                                        type: "error",
                                        title: "Adding Doctor Failed",
                                        message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                        offset: 0
                                    });
                                });
                        } else {
                            this.show_dialog = false;
                            loading.close();
                            this.$notify({
                                type: "error",
                                title: "Adding Doctor Failed",
                                message: `${this.form.name} does not follow the correct naming convention\n (Ex: Dela Cruz, Jose Juan Jr.)`,
                                offset: 0
                            });
                            this.formResetFields();
                        }
                    }
                    break;
                case "edit":
                    if (
                        this.edit_object.name == this.form.name &&
                        this.edit_object.is_active === this.form.is_active &&
                        this.edit_object.is_parttime === this.form.is_parttime
                    ) {
                        this.$notify({
                            type: "info",
                            title: "Editing Failed",
                            message:
                                "No changes are made to the doctor's record",
                            offset: 0
                        });
                        this.show_dialog = false;
                        this.formResetFields();
                        loading.close();
                    } else {
                        this.form.name = this.form.name.trim();
                        this.form.id = this.edit_object.id;
                        axios
                            .put("edit_doctor", this.form)
                            .then(response => {
                                if (
                                    response.status >= 200 &&
                                    response.status <= 299
                                ) {
                                    var index = this.doctors.findIndex(
                                        object => object.id == response.data.id
                                    );
                                    if (index !== undefined) {
                                        this.doctors[index].name =
                                            response.data.name;
                                        this.doctors[index].is_parttime =
                                            response.data.is_parttime;
                                        this.doctors[index].is_active =
                                            response.data.is_active;
                                    }
                                    this.doctor_list_compress.push(
                                        this.trimToCompare(response.data.name)
                                    );
                                    this.show_dialog = false;
                                    this.formResetFields();
                                    this.edit_object = "";
                                    loading.close();
                                    this.$notify({
                                        type: "success",
                                        title: "Editing Doctor Successful",
                                        message: `Successfully edited ${response.data.name}`,
                                        offset: 0
                                    });
                                } else {
                                    this.show_dialog = false;
                                    this.formResetFields();
                                    this.edit_object = "";
                                    loading.close();
                                    this.$notify({
                                        type: "error",
                                        title: "Editing Doctor Failed",
                                        message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                        offset: 0
                                    });
                                }
                            })
                            .catch(error => {
                                this.show_dialog = false;
                                this.formResetFields();
                                this.edit_object = "";
                                loading.close();
                                this.$notify({
                                    type: "error",
                                    title: "Editing Doctor Failed",
                                    message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                    offset: 0
                                });
                            });
                    }
                    break;
            }
        },
        deleteDoctor(data) {
            this.$confirm(
                `Are you sure you want to delete record for doctor ${data.name}?`,
                "Confirm Delete",
                {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios.delete(`delete_doctor/${data.id}`).then(response => {
                        if (response.status > 199 && response.status < 203) {
                            if (response.data.status == "success") {
                                var index = this.doctors.findIndex(
                                    object => object.id == data.id
                                );
                                if (index !== undefined) {
                                    this.doctors.splice(index, 1);
                                }
                            }
                            this.$notify({
                                type: response.data.status,
                                title: response.data.title,
                                message: response.data.message,
                                offset: 0
                            });
                        }
                    });
                })
                .catch(action => {
                    this.$notify({
                        type: "info",
                        title: "Deleting Doctor Cancelled",
                        message: `Delete method cancelled. No changes were made`,
                        offset: 0
                    });
                });
        },
        formDialog(key) {
            /**
             * TODO: Don't use if else statement here, you can use the parameter as the #id because it is also string
             **/
            switch (key) {
                case "import_data":
                    this.dialogtitle = "Import Excel";
                    this.fullscreen = true;
                    this.isimport = true;
                    this.dialogExcelFile = true;
                    break;
                case "export_data":
                    this.dialogtitle = "Download / Export Excel";
                    this.fullscreen = false;
                    this.isimport = false;
                    this.dialogExcelFile = true;
                    break;
            }
        },
        handleAddButtonClick() {
            this.show_dialog = true;
            this.form.form_type = "add";
            this.formResetFields();
        },
        handleEdit(row_data) {
            this.formResetFields();
            this.edit_object = row_data;
            this.show_dialog = true;
            this.form.form_type = "edit";
            this.form.name = row_data.name;
            this.form.is_parttime = Boolean(row_data.is_parttime);
            this.form.is_active = Boolean(row_data.is_active);
        },
        handleDelete(row_data) {
            this.deleteDoctor(row_data);
        },
        employmentType(data) {
            if (data.is_parttime) {
                return "Part-time";
            }
            return "Full-time";
        },
        employmentStatus(data) {
            if (data.is_active) {
                return "Active";
            }
            return "Inactive";
        },
        handleCurrentChange(value) {
            this.page = value;
        },
        formResetFields() {
            if (this.$refs.doctors_form !== undefined) {
                this.$refs.doctors_form.resetFields();
            }
        },
        testName(name) {
            var regex = new RegExp(
                /^([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*,\s([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*$/
            );
            if (regex.test(name)) {
                return true;
            }
            return false;
        },
        buildFullName() {
            if (this.form.suffix.trim()) {
                this.form.name = `${this.form.last_name.trim()}, ${this.form.first_name.trim()} ${this.form.suffix.trim()} ${this.form.middle_name.trim()}`;
            } else {
                this.form.name = `${this.form.last_name.trim()}, ${this.form.first_name.trim()} ${this.form.middle_name.trim()}`;
            }
        },
        handleCurrentChangeTable(value) {
            this.tablepage = value;
            this.tablelength = this.preview_data[0].length;
            this.exceldata = this.preview_data[0];
            this.exceldata = this.exceldata.slice(
                this.page_size * this.tablepage - this.page_size,
                this.page_size * this.tablepage
            );
        },
        uploadToDatabase() {
            var _this = this;
            _this.getDoctors();
            if (
                _this.excel_validation_error[0].length < 1 &&
                _this.excel_validation_error[1].length < 1 &&
                _this.preview_data.length > 0
            ) {
                axios
                    .post("import_doctor_list", _this.preview_data)
                    .then(function(res) {
                        _this.$notify({
                            type: "success",
                            title: "Import",
                            message: "Data imported successfully!"
                        });
                    })
                    .catch(function(res) {});
            } else {
                _this.$notify({
                    type: "warning",
                    title: "Import",
                    message: "Upload request error, please check your file."
                });
            }
        },
        handleExceedFile(files, fileList) {
            this.$notify({
                type: "info",
                title: "Import",
                message: "You can only upload one file at a time"
            });
        },
        handleRemoveFile(file, fileList) {
            this.getDoctors();
            this.sheet_length = "";
            this.tablepage = 1;
            this.tablelength = 0;
            this.preview_data = [];
            this.exceldata = [];
            this.excel_validation_error = [[], []];
            this.doctor_list_compress = [];
            this.is_preview = false;
            this.$notify({
                type: "info",
                title: "Cancelled",
                message: file.name + " was removed"
            });
        },
        trimToCompare(text) {
            return text
                .trim()
                .toLowerCase()
                .replace(/\s/g, "");
        },
        fileData(file, fileList) {
            var _this = this;
            var header_required = [
                "physician_name",
                "is_active",
                "is_parttime"
            ];
            var reader = new FileReader();
            reader.readAsArrayBuffer(file.raw);
            reader.onloadend = function(e) {
                var data = new Uint8Array(reader.result);
                var wb = XLSX.read(data, { type: "array" });
                var i = 0;
                let sheetName = wb.SheetNames[i];
                let worksheet = wb.Sheets[sheetName];
                try {
                    var range = XLSX.utils.decode_range(worksheet["!ref"]);
                    if (range.e.r < 1) {
                        _this.excel_validation_error[1].push({
                            id:
                                "wsc" +
                                Math.random()
                                    .toString(36)
                                    .substring(7) +
                                (i + 1),
                            value: "",
                            message:
                                "It looks like you don't have any data in this page ",
                            cell_position: "worksheet #" + (i + 1)
                        });
                    }
                } catch (error) {}
                for (var R = range.s.r; R <= range.e.r; ++R) {
                    for (var C = range.s.c; C <= range.e.c; ++C) {
                        var cellref = XLSX.utils.encode_cell({ c: C, r: R });
                        var cell_position =
                            "#" +
                            (i + 1) +
                            " " +
                            (C + 1 + 9).toString(36).toUpperCase() +
                            (R + 1);
                        if (!worksheet[cellref]) {
                            if (R == 0 && C < 3) {
                                _this.excel_validation_error[0].push({
                                    id:
                                        "wsh" +
                                        Math.random()
                                            .toString(36)
                                            .substring(7) +
                                        (i + 1),
                                    value: "",
                                    message: "Header must have 3 column.",
                                    cell_position: cell_position
                                });
                            }
                            if (R > 0 && C < 3) {
                                if (C == 0) {
                                    _this.excel_validation_error[1].push({
                                        id:
                                            "wsc" +
                                            Math.random()
                                                .toString(36)
                                                .substring(7) +
                                            (i + 1),
                                        value: "",
                                        message:
                                            "This cell must contain Physician Name, format(LastName, FirstName MiddleName) ",
                                        cell_position: cell_position
                                    });
                                } else if (C > 0 && C < 3) {
                                    _this.excel_validation_error[1].push({
                                        id:
                                            "wsc" +
                                            Math.random()
                                                .toString(36)
                                                .substring(7) +
                                            (i + 1),
                                        value: "",
                                        message:
                                            "This cell must contain 'Yes or No' only ",
                                        cell_position: cell_position
                                    });
                                }
                            }
                            continue;
                        }
                        var cell = worksheet[cellref];
                        if (R == 0 && C < 3) {
                            var column_cell = _this.trimToCompare(cell.v);
                            if (header_required.indexOf(column_cell) == "-1") {
                                _this.excel_validation_error[0].push({
                                    id:
                                        "wsh" +
                                        Math.random()
                                            .toString(36)
                                            .substring(7) +
                                        (i + 1),
                                    value: cell.v,
                                    message:
                                        "Required header did not match, download the sample excel file",
                                    cell_position: cell_position
                                });
                            }
                        }
                        if (R > 0 && C < 3) {
                            if (C == 0) {
                                if (cell.v.match(/[^,]+,[^,]+/g) == null) {
                                    _this.excel_validation_error[1].push({
                                        id:
                                            "wsc" +
                                            Math.random()
                                                .toString(36)
                                                .substring(7) +
                                            (i + 1),
                                        value: cell.v,
                                        message:
                                            "must contain Physician Name, format(LastName, FirstName MiddleName) ",
                                        cell_position: cell_position
                                    });
                                } else if (
                                    cell.v.match(/[^,]+,[^,]+/g).length > 0
                                ) {
                                    if (
                                        cell.v.match(/[^,]+,[^,]+/g).length > 1
                                    ) {
                                        _this.excel_validation_error[1].push({
                                            id:
                                                "wsc" +
                                                Math.random()
                                                    .toString(36)
                                                    .substring(7) +
                                                (i + 1),
                                            value: cell.v,
                                            message:
                                                "must contain only one Physician Name, format(LastName, FirstName MiddleName) ",
                                            cell_position: cell_position
                                        });
                                    } else {
                                        var compare = cell.v.match(
                                            /[^,]+,[^,]+/g
                                        );
                                        if (
                                            _this.trimToCompare(compare[0]) !=
                                            _this.trimToCompare(cell.v)
                                        ) {
                                            _this.excel_validation_error[1].push(
                                                {
                                                    id:
                                                        "wsc" +
                                                        Math.random()
                                                            .toString(36)
                                                            .substring(7) +
                                                        (i + 1),
                                                    value: cell.v,
                                                    message:
                                                        "must contain only one Physician Name, format(LastName, FirstName MiddleName) ",
                                                    cell_position: cell_position
                                                }
                                            );
                                        }
                                    }
                                }
                            } else if (C > 0 && C < 3) {
                                if (
                                    _this.trimToCompare(cell.v) != "yes" &&
                                    _this.trimToCompare(cell.v) != "no"
                                ) {
                                    _this.excel_validation_error[1].push({
                                        id:
                                            "wsc" +
                                            Math.random()
                                                .toString(36)
                                                .substring(7) +
                                            (i + 1),
                                        value: cell.v,
                                        message:
                                            "This cell must contain 'Yes or No' only ",
                                        cell_position: cell_position
                                    });
                                }
                            }
                        }
                    }
                }
                _this.preview_data.push(XLSX.utils.sheet_to_json(worksheet));
                if (
                    _this.excel_validation_error[0].length < 1 &&
                    _this.excel_validation_error[1].length < 1
                ) {
                    _this.tablelength = _this.preview_data[0].length;
                    _this.exceldata = _this.preview_data[0];
                    _this.tablepage = parseInt(1);
                    _this.exceldata = _this.exceldata.slice(
                        _this.page_size * _this.tablepage - _this.page_size,
                        _this.page_size * _this.tablepage
                    );

                    _this.is_preview = true;
                } else {
                    _this.is_preview = false;
                }
            };
        },
        exportExcel() {
            this.doctor_export = [];
            this.doctors.forEach(doctor => {
                this.doctor_export.push({
                    Physician_Name: doctor.name,
                    Is_active: doctor.is_active ? "Yes" : "No",
                    Is_parttime: doctor.is_parttime ? "Yes" : "No"
                });
            });
            var doctors = XLSX.utils.json_to_sheet(this.doctor_export);
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, doctors, "Physician List");
            XLSX.writeFile(wb, "Physician_List.xlsx");
        },
        getTemplate() {
            window.open(
                window.location.origin +
                    "/template/Import_DoctorList_Template.xlsx"
            );
        },
        formatNumber(row, column, cellValue, index) {
            return new Intl.NumberFormat().format(cellValue);
        },
        firstLetterOfWordUpperCase(row, column, cellValue, index) {
            return cellValue.charAt(0).toUpperCase() + cellValue.slice(1);
        }
    },
    mounted() {
        this.getDoctors();
        this.getBatch();
    }
};
</script>

<style lang="css">
#search-box {
    margin-bottom: 10px;
}
.block-button,
.block-button button {
    width: 100%;
}
.doctors-form-radio-button {
    width: 100%;
}
</style>

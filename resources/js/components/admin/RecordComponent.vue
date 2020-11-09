<template>
    <div>
        
        <!-- Header -->
        <div class="row header-top"> 
            <div class="header-title-parent" style="padding-top:2px !important;padding-bottom:2px !important;">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Records
                </span>
            </div>
        </div>
        <!-- End Header -->
        
        <div class="row">
            <div class="col-sm-10" align="left">
                <div style="margin-bottom: 10px"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <el-table :data="listData">
                    <el-table-column
                        width="115"
                        label="Philhealth"
                        prop="philhealth_number"
                    >
                    </el-table-column>
                    <el-table-column
                        width="177"
                        label="Patient"
                        prop="first_name"
                    >
                    </el-table-column>
                    <el-table-column
                        width="100"
                        label="Admit"
                        prop="admission_date"
                    >
                    </el-table-column>
                    <el-table-column
                        width="110"
                        label="Discharge"
                        prop="discharge_date"
                    >
                    </el-table-column>
                    <el-table-column
                        width="120"
                        label="Diagnosis"
                        prop="final_diagnosis"
                    >
                    </el-table-column>
                    <el-table-column
                        width="115"
                        label="Record type"
                        prop="record_type"
                    >
                    </el-table-column>
                    <el-table-column
                        width="115"
                        label="Total fee"
                        prop="total_fee"
                    >
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
                <el-dialog
                    title="Patient Details"
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
                            <el-input
                                v-model="form.total"
                                autocomplete="off"
                            ></el-input>
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
                        <el-form-item
                            label="Hospital code"
                            :label-width="formLabelWidth"
                        >
                            <el-select
                                v-model="form.hospital_code"
                                @change="onChange(form.hospital_code)"
                                placeholder="Please select"
                            >
                                <el-option
                                    label="DFBDSMH"
                                    value="1"
                                ></el-option>
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
                        <el-button @click="dialogFormVisible = false"
                            >Cancel</el-button
                        >
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
import constants from "../../constants";
("use strict");
export default {
    data() {
        return {
            page: 1,
            pageSize: 10,
            search: "",
            staff: [],
            data: [],
            budgetInfo: [],
            rules: {
                start_date: [
                    {
                        required: true,
                        message: "Start date is required.",
                        trigger: "blur"
                    }
                ],
                total: [
                    {
                        required: true,
                        message: "Amount is required.",
                        trigger: "blur"
                    }
                ],
                end_date: [
                    {
                        required: true,
                        message: "End date is required.",
                        trigger: "blur"
                    }
                ],
                hospital_code: [
                    {
                        required: true,
                        message: "Hospital code is required.",
                        trigger: "blur"
                    }
                ]
            },
            filters: [
                {
                    prop: [
                        "patient.philhealth_number",
                        "patient.first_name",
                        "admission_date",
                        "discharge_date"
                    ],
                    value: ""
                }
            ],
            gridData: [
                {
                    psfname: "",
                    psmname: "",
                    pslname: ""
                }
            ],
            actionCol: {
                label: "Actions",
                props: {
                    align: "center"
                },
                buttons: [
                    {
                        props: {
                            type: "info",
                            icon: "el-icon-info",
                            circle: true,
                            size: "mini"
                        },
                        handler: row => {
                            this.dialogTableVisible = true;
                            this.gridData[0].psfname = row.psfname;
                            this.gridData[0].psmname = row.psmname;
                            this.gridData[0].pslname = row.pslname;
                        }
                    },
                    {
                        props: {
                            type: "primary",
                            icon: "el-icon-edit",
                            circle: true,
                            size: "mini"
                        },
                        handler: row => {
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
                        }
                    }
                ]
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
                codeholder: ""
            },
            formLabelWidth: "120px"
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
                        .includes(this.search.toLowerCase()) ||
                    data.final_diagnosis
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.record_type
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
        masknumber: function(num) {
            num = parseFloat(num)
                .toFixed(2)
                .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            return num;
        },
        setPage(val) {
            this.page = val;
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        handleView(index, row) {
            axios
                .get("adminpersonnel_get/" + row.id)
                .then(response => {
                    this.staff = response.data.personnels;
                })
                .catch(function(error) {});
            this.dialogTableVisible = true;
        },
        handleDelete(index, row) {},
        deleteRecord: function(id, res) {
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
                    axios.post("delete_record/" + id).then(function(response) {
                        if (response.status > 199 && response.status < 203) {
                            _this.$message({
                                type: "warning",
                                message: "Succesfully! Deleted"
                            });
                            res(id);
                        }
                    });
                })
                .catch(action => {
                    this.$message({
                        type: "success",
                        message: action === "cancel" ? "Canceled" : "No changes"
                    });
                });
        },
        getRecord: function() {
            axios
                .get("adminrecord_get")
                .then(response => {
                    response.data.forEach(entry => {
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
                        entry.hospital_id =
                            constants.hospital_code[
                                Number(entry.hospital_id) - 1
                            ];
                        entry.total_fee = this.masknumber(entry.total_fee);
                    });
                    this.data = response.data;
                })
                .catch(function(error) {});
        },
        clearfield: function() {
            this.form.start_date = "";
            this.form.total = "";
            this.form.end_date = "";
        },
        addBudget: function(mode) {
            switch (mode) {
                case "add":
                    this.axios
                        .post("adminadd_budget", this.form)
                        .then(
                            this.getBudget(),
                            (this.dialogFormVisible = false)
                        )
                        .catch(function(error) {});
                    break;
                case "edit":
                    axios
                        .post("adminedit_budget/" + this.form.id, this.form)
                        .then(
                            this.getBudget(),
                            (this.dialogFormVisible = false)
                        )
                        .catch(function(error) {});
                    break;
            }
        }
    },
    mounted() {
        this.getRecord();
    }
};
</script>

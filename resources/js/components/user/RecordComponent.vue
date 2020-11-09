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
        
        <div class="row"></div>
        <div class="card">
            <div class="card-body">
                <div id="test">
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
                            column-key="ase"
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
                            width="125"
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
                        <el-table-column
                            width="130"
                            align="right"
                            fixed="right"
                        >
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
                                    content="Add Contribution"
                                    placement="top"
                                    v-if="scope.row.totalPersonnel == 0"
                                >
                                    <el-button
                                        size="mini"
                                        type="success"
                                        icon="el-icon-plus"
                                        circle
                                        @click="
                                            handleAddRecord(
                                                scope.$index,
                                                scope.row
                                            )
                                        "
                                    >
                                    </el-button>
                                </el-tooltip>
                                <el-tooltip
                                    class="item"
                                    effect="light"
                                    content="view"
                                    placement="top"
                                    v-if="scope.row.totalPersonnel > 0"
                                >
                                    <el-button
                                        size="mini"
                                        type="info"
                                        icon="el-icon-info"
                                        circle
                                        @click="
                                            handleView(scope.$index, scope.row)
                                        "
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
                                        @click="
                                            handleDelete(
                                                scope.$index,
                                                scope.row
                                            )
                                        "
                                    ></el-button>
                                </el-tooltip>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>

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
            <!-- Show personnel Details -->
            <el-dialog
                title="Personnel Details"
                :visible.sync="dialogTableVisible"
                :close-on-press-escape="false"
                :close-on-click-modal="false"
            >
                <div class="row">
                    <div class="col-sm-12" align="right">
                        <el-button
                            right
                            type="primary"
                            size="medium"
                            @click="triggerAdd('update')"
                            >Add Staff</el-button
                        >
                    </div>
                </div>

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
                        width="200"
                    ></el-table-column>
                    <el-table-column
                        property="total_fee"
                        label="Total"
                        width="200"
                    ></el-table-column>

                    <el-table-column
                        align="right"
                        fixed="right"
                        label="Action"
                        width="formLabelWidth"
                    >
                        <template slot-scope="scopePersonnel">
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
                                    @click="
                                        handleDeleteContribution(
                                            scopePersonnel.$index,
                                            scopePersonnel.row
                                        )
                                    "
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
            </el-dialog>
            <!-- Show Patient Details -->
        </div>
    </div>
</template>
<style></style>

<script>
"use strict";
export default {
    data() {
        return {
            container: [],
            tabledata: [],
            filtered: [],
            page: 1,
            total: 0,
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
                        "philhealth",
                        "pfname",
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
            delete_contribution: {
                id: "",
                data: [],
                deletedAmmount: "",
                attendingCounter: "",
                toAdd: 0
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
        triggerAdd(mode) {
            switch (mode) {
                case "update":
                    var counter = 0;
                    var holder = 0.0;
                    this.container.type = true;
                    this.container.contributions.forEach(element => {
                        if (element.contribution == "Attending Physician") {
                            counter += 1;
                            this.container.totalAttending = counter;
                            var temp = parseFloat(element.credit).toFixed(2);
                            holder = (Number(holder) + Number(temp)).toFixed(2);
                            this.container.totalContributions = Number(holder);
                        }
                    });
                    this.container.total_fee = parseFloat(
                        this.container.total_fee.replace(/,/g, "")
                    );
                    break;
                default:
                    this.container.total_fee = parseFloat(
                        this.container.total_fee.replace(/,/g, "")
                    );
            }
            this.$emit("add-trigger", this.container);
        },
        masknumber: function(theform, mode) {
            var num = theform,
                rounded;
            if (mode == true) {
                var with2Decimals = num
                    .toString()
                    .match(/^-?\d+(?:\.\d{0,4})?/)[0]
                    .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            } else {
                var with2Decimals = num
                    .toString()
                    .match(/^-?\d+(?:\.\d{0,4})?/)[0];
            }
            return with2Decimals;
        },
        setPage(val) {
            this.page = val;
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        handleDelete(index, row) {
            var data = this.data;

            this.deleteRecord("delete_record/", row.id, res_value => {
                if (res_value) {
                    data.splice(data.indexOf(row), 1);
                    this.getRecord();
                }
            });
        },
        handleDeleteContribution(index, row) {
            this.tempStaff = this.staff;
            this.staff.forEach(element => {
                element.total_fee = Number(
                    this.masknumber(element.total_fee.replace(/,/g, ""), false)
                );
            });
            this.tabledata = this.staff;
            var attendingCounter = 0;
            var deletedAmmount = 0;
            this.tabledata.forEach(el => {
                if (
                    el.contribution == "Attending Physician" &&
                    el.cid != row.cid
                ) {
                    attendingCounter += 1;
                }
                if (el.cid == row.cid) {
                    deletedAmmount = el.total_fee;
                }
            });
            var toAdd = Number(
                this.masknumber(deletedAmmount, false) / attendingCounter
            );
            this.tabledata.forEach(el => {
                if (
                    el.contribution == "Attending Physician" &&
                    el.cid != row.cid
                ) {
                    el.total_fee =
                        Number(this.masknumber(el.total_fee, false)) + toAdd;
                } else {
                    el.total_fee = this.masknumber(el.total_fee, false);
                }
            });

            this.delete_contribution.id = row.cid;
            this.delete_contribution.data = this.tabledata;
            this.delete_contribution.deletedAmmount = deletedAmmount;
            this.delete_contribution.attendingCounter = attendingCounter;
            this.delete_contribution.toAdd = toAdd;
            var counter = 0;
            this.deleteRecord(
                "contribution_delete/",
                row.cid,
                this.delete_contribution,
                "update",
                res_value => {
                    if (res_value) {
                        this.tabledata.splice(this.tabledata.indexOf(row), 1);
                        this.dialogTableVisible = false;
                        this.getRecord();
                    }
                }
            );
        },
        recomputePF(pf) {
            let a = 0;
            let tempAmount = 0;
            var numOfAttending = this.staff.filter(element => {
                return element.contribution == "Attending Physician";
            });
            this.staff.forEach(element => {
                if (element.contribution == "Attending Physician") {
                    tempAmount = element.total_fee;
                }
            });
            a = parseFloat(pf.replace(/,/g, "")) / numOfAttending.length;
        },
        handleAddRecord(index, row) {
            this.container = row;

            this.container.total_fee = parseFloat(
                this.container.total_fee.replace(/,/g, "")
            );

            this.$emit("add-trigger", this.container);
        },
        handleView(index, row) {
            this.container = row;
            this.personnel_get(index, row);
            this.dialogTableVisible = true;
        },
        personnel_get(index, row) {
            axios
                .post("personnels_get/" + row.id)
                .then(response => {
                    response.data.forEach(entry => {
                        var temp = entry.total_fee;
                        entry.total_fee = this.masknumber(temp, true);
                    });

                    this.staff = response.data;
                })
                .catch(function(error) {});
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
        deleteRecord: function(route, id, data, mode, cb) {
            var _this = this;
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
                    axios.post(route + id, data).then(function(response) {
                        if (response.status > 199 && response.status < 203) {
                            _this.open_notif(
                                "success",
                                "Success",
                                "Succesfully! Deleted"
                            );
                        }
                        cb(id);
                    });
                })
                .catch(action => {
                    this.$message({
                        type: "success",
                        message: action === "cancel" ? "Canceled" : "No changes"
                    });

                    if (mode == "update") {
                        var toReduc =
                            Number(_this.delete_contribution.deletedAmmount) /
                            Number(_this.delete_contribution.attendingCounter);
                        _this.tabledata.forEach(el => {
                            if (
                                el.contribution == "Attending Physician" &&
                                el.cid != Number(_this.delete_contribution.id)
                            ) {
                                el.total_fee =
                                    Number(
                                        _this.masknumber(el.total_fee, false)
                                    ) - toReduc;
                            }
                        });
                        _this.tabledata.forEach(el => {
                            el.total_fee = _this.masknumber(el.total_fee, true);
                        });
                    }
                });
        },
        getRecord: function() {
            axios
                .get("record_get")
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

                        entry.total_fee = this.masknumber(
                            entry.total_fee,
                            true
                        );
                        entry.totalPersonnel = entry.contributions.length;
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
                    axios
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

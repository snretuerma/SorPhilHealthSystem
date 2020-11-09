<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2 class="font-weight-bold">
                    <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Records
                </h2>
            </div>
        </div>
        <hr />
        <!-- End Header -->

        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
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
                        width="160"
                        label="Diagnosis"
                        prop="final_diagnosis"
                    >
                    </el-table-column>
                    <el-table-column
                        width="130"
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
                        width="115"
                        label="Hospital"
                        prop="hospital_id"
                    >
                    </el-table-column>
                    <el-table-column
                        width="80"
                        align="right"
                        fixed="right"
                        label="Action"
                    >
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
        <!-- Card Ends Here -->

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
                    width="200"
                ></el-table-column>
                <el-table-column
                    property="hospital_code"
                    label="Hospital"
                    width="200"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Patient Details -->
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
            dialogTableVisible: false,
            gridData: [
                {
                    psfname: "",
                    psmname: "",
                    pslname: "",
                    hospital_id: ""
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
        handleCurrentChange(val) {
            this.page = val;
        },
        handleView(index, row) {
            axios
                .get("observerPersonnelList/" + row.id)
                .then(response => {
                    response.data.personnels.forEach(el => {
                        el.hospital_code =
                            constants.hospital_code[Number(el.hospital_id)];
                    });
                    this.staff = response.data.personnels;
                    console.log(this.staff);
                })
                .catch(function(error) {});
            this.dialogTableVisible = true;
        },
        getRecordList: function() {
            axios
                .get("observerRecordList")
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
        }
    },
    mounted() {
        this.getRecordList();
        // console.log(this.data);
    }
};
</script>
